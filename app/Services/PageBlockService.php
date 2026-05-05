<?php

namespace App\Services;

use App\Models\Block;
use Illuminate\Support\Collection;

class PageBlockService
{
    // Загружает блоки страницы, обогащает служебными данными и сортирует по порядку.
    public function getSortedBlocks(): Collection
    {
        return $this->getSortedBlocksByPlacement(Block::PLACEMENT_CONTENT);
    }

    public function getSortedBlocksByPlacement(string $placement): Collection
    {
        return Block::query()
            ->with('variables')
            ->where('placement', $placement)
            ->get()
            ->map(function (Block $block) {
                $variables = $block->variables;
                $order = (int) optional($variables->firstWhere('name', 'order'))->default_value;
                $isVisible = optional($variables->firstWhere('name', 'visibility'))->default_value ?? '1';

                return [
                    'my_id' => $order,
                    'block_id' => $block->id,
                    'placement' => $block->placement,
                    'is_system' => $block->is_system,
                    'is_visible' => $isVisible === '1',
                    'data' => $block->blade_template,
                ];
            })
            ->sortBy('my_id')
            ->values();
    }

    // Возвращает список блоков для селекта и навигации в админской панели.
    public function getEditorBlocks(): Collection
    {
        return Block::query()
            ->with('variables')
            ->get()
            ->map(function (Block $block) {
                $variables = $block->variables;
                $order = (int) optional($variables->firstWhere('name', 'order'))->default_value;
                $isVisible = optional($variables->firstWhere('name', 'visibility'))->default_value ?? '1';

                return [
                    'id' => $block->id,
                    'name' => $block->name ?: 'Без названия',
                    'description' => $block->description ?: '',
                    'order' => $order,
                    'placement' => $block->placement,
                    'is_system' => $block->is_system,
                    'is_visible' => $isVisible === '1',
                ];
            })
            ->sortBy([
                ['placement', 'asc'],
                ['order', 'asc'],
                ['id', 'asc'],
            ])
            ->values();
    }
}
