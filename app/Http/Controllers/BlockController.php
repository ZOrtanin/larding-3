<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\BlockTemplate;
use App\Models\BlockVariable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class BlockController extends Controller
{
    // Служебные переменные блока, которые не показываются в редакторе пользовательских полей.
    private const SYSTEM_VARIABLE_NAMES = ['order', 'visibility'];

    // Создаёт новый блок страницы вместе с системными и пользовательскими переменными.
    public function create(Request $request): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'placement' => ['nullable', 'in:'.implode(',', Block::PLACEMENTS)],
            'variables' => ['nullable', 'array'],
            'variables.*.name' => ['nullable', 'string', 'max:255'],
            'variables.*.label' => ['nullable', 'string', 'max:255'],
            'variables.*.type' => ['nullable', 'in:text,textarea,color,image,boolean'],
            'variables.*.default_value' => ['nullable', 'string'],
            'variables.*.required' => ['nullable', 'boolean'],
        ]);

        $name = $validated['name'];
        $content = $validated['content'];
        $description = $validated['description'] ?? null;
        $placement = $validated['placement'] ?? Block::PLACEMENT_CONTENT;
        $lastBlock = Block::with('variables')
            ->where('placement', $placement)
            ->get()
            ->flatMap(function ($block) {
                return $block->variables
                    ->where('name', 'order')
                    ->pluck('default_value');
            })->map(function ($value) {
                return (int) $value;
            })->max() ?? 0;

        $block = Block::create([
            'name' => $name,
            'description' => $description,
            'blade_template' => $content,
            'placement' => $placement,
        ]);

        $block->variables()->createMany([
            [
                'name' => 'order',
                'label' => $name,
                'type' => 'text',
                'default_value' => (string) ($lastBlock + 1),
                'required' => true,
            ],
            [
                'name' => 'visibility',
                'label' => 'Видимость',
                'type' => 'boolean',
                'default_value' => '1',
                'required' => true,
            ],
        ]);

        $customVariables = $this->prepareCustomVariables($validated['variables'] ?? []);

        if ($customVariables !== []) {
            $block->variables()->createMany($customVariables);
        }

        return Redirect::to('/');
    }

    // Возвращает данные блока для загрузки в редактор админки.
    public function show(Block $block): JsonResponse {
        return response()->json([
            'id' => $block->id,
            'name' => $block->name,
            'description' => $block->description,
            'content' => $block->blade_template,
            'placement' => $block->placement,
            'is_system' => $block->is_system,
            'variables' => $block->variables()
                ->whereNotIn('name', self::SYSTEM_VARIABLE_NAMES)
                ->orderBy('id')
                ->get(['name', 'label', 'type', 'default_value', 'required']),
        ]);
    }

    // Возвращает список доступных шаблонов блоков.
    public function listTemplates(): JsonResponse {
        $templates = BlockTemplate::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'description']);

        return response()->json($templates);
    }

    // Возвращает содержимое выбранного шаблона блока.
    public function showTemplate(BlockTemplate $blockTemplate): JsonResponse {
        return response()->json([
            'id' => $blockTemplate->id,
            'name' => $blockTemplate->name,
            'slug' => $blockTemplate->slug,
            'description' => $blockTemplate->description,
            'content' => $blockTemplate->blade_template,
        ]);
    }

    // Обновляет блок страницы и пересоздаёт его пользовательские переменные.
    public function update(Request $request, Block $block): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'placement' => ['nullable', 'in:'.implode(',', Block::PLACEMENTS)],
            'variables' => ['nullable', 'array'],
            'variables.*.name' => ['nullable', 'string', 'max:255'],
            'variables.*.label' => ['nullable', 'string', 'max:255'],
            'variables.*.type' => ['nullable', 'in:text,textarea,color,image,boolean'],
            'variables.*.default_value' => ['nullable', 'string'],
            'variables.*.required' => ['nullable', 'boolean'],
        ]);

        $block->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'blade_template' => $validated['content'],
            'placement' => $validated['placement'] ?? $block->placement,
        ]);

        $block->variables()
            ->whereNotIn('name', self::SYSTEM_VARIABLE_NAMES)
            ->delete();

        $customVariables = $this->prepareCustomVariables($validated['variables'] ?? []);

        if ($customVariables !== []) {
            $block->variables()->createMany($customVariables);
        }

        return Redirect::to('/');
    }

    // Удаляет блок страницы.
    public function delete(Block $block): JsonResponse {
        if ($block->is_system) {
            return response()->json([
                'ok' => false,
                'message' => 'Системный layout-блок нельзя удалить.',
            ], 422);
        }

        $block->delete();

        return response()->json([
            'ok' => true,
            'deleted' => true,
            'block_id' => $block->id,
        ]);
    }

    // Перемещает блок на одну позицию вверх.
    public function moveUp(Request $request, Block $block): JsonResponse {
        return $this->swapBlockOrder($block, 'up');
    }

    // Перемещает блок на одну позицию вниз.
    public function moveDown(Request $request, Block $block): JsonResponse {
        return $this->swapBlockOrder($block, 'down');
    }

    // Переключает видимость блока на сайте.
    public function toggleVisibility(Block $block): JsonResponse {
        $visibilityVar = $block->variables()->where('name', 'visibility')->first();

        if (! $visibilityVar) {
            $visibilityVar = $block->variables()->create([
                'name' => 'visibility',
                'label' => 'Видимость',
                'type' => 'boolean',
                'default_value' => '1',
                'required' => true,
            ]);
        }

        $nextVisibility = $visibilityVar->default_value === '1' ? '0' : '1';
        $visibilityVar->update([
            'default_value' => $nextVisibility,
        ]);

        return response()->json([
            'ok' => true,
            'block_id' => $block->id,
            'visibility' => $nextVisibility,
        ]);
    }

    // Меняет порядок блока местами с соседним блоком в выбранном направлении.
    private function swapBlockOrder(Block $block, string $direction): JsonResponse {
        $currentVar = $block->variables()->where('name', 'order')->first();
        if (! $currentVar) {
            return response()->json(['ok' => false, 'message' => 'Order variable not found'], 404);
        }

        $currentOrder = (int) $currentVar->default_value;
        $driver = DB::getDriverName();
        $numberCast = $driver === 'pgsql' ? 'INTEGER' : 'SIGNED';
        $regexOperator = $driver === 'pgsql' ? '~' : 'REGEXP';

        $neighborQuery = BlockVariable::query()
            ->whereHas('block', function ($query) use ($block) {
                $query->where('placement', $block->placement);
            })
            ->where('name', 'order')
            ->where('block_id', '!=', $block->id)
            ->whereRaw("default_value {$regexOperator} '^(-?[0-9]+)$'");

        if ($direction === 'up') {
            $neighborQuery
                ->whereRaw("CAST(default_value AS {$numberCast}) < ?", [$currentOrder])
                ->orderByRaw("CAST(default_value AS {$numberCast}) DESC");
        } else {
            $neighborQuery
                ->whereRaw("CAST(default_value AS {$numberCast}) > ?", [$currentOrder])
                ->orderByRaw("CAST(default_value AS {$numberCast}) ASC");
        }

        $neighborVar = $neighborQuery->first();
        if (! $neighborVar) {
            return response()->json([
                'ok' => true,
                'moved' => false,
                'block_id' => $block->id,
                'order' => $currentOrder,
            ]);
        }

        DB::transaction(function () use ($currentVar, $neighborVar, $currentOrder) {
            $currentVar->default_value = $neighborVar->default_value;
            $neighborVar->default_value = $currentOrder;
            $currentVar->save();
            $neighborVar->save();
        });

        return response()->json([
            'ok' => true,
            'moved' => true,
            'block_id' => $block->id,
            'order' => (int) $currentVar->default_value,
        ]);
    }

    // Подготавливает пользовательские переменные блока к сохранению в базу.
    private function prepareCustomVariables(array $variables): array {
        return collect($variables)
            ->map(function ($variable) {
                $name = trim((string) Arr::get($variable, 'name', ''));
                $label = trim((string) Arr::get($variable, 'label', ''));

                if ($name === '' || in_array($name, self::SYSTEM_VARIABLE_NAMES, true)) {
                    return null;
                }

                return [
                    'name' => $name,
                    'label' => $label !== '' ? $label : $name,
                    'type' => Arr::get($variable, 'type', 'text'),
                    'default_value' => Arr::get($variable, 'default_value'),
                    'required' => (bool) Arr::get($variable, 'required', false),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
