<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    // Создаёт лид из внешнего API-запроса.
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'form_lead' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'digits_between:7,25'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['nullable', 'string', 'max:5000'],
        ]);

        $content = $this->buildContent($validated);
        $safeContent = $this->sanitizeLeadContent($content);

        $lead = Lead::query()->create([            
            'name' => 'Новая заявка API '.$validated['form_lead'],
            'content' => $safeContent,
            'status' => 'new',
            'comments' => null,
        ]);

        Notification::query()->create([
            'type' => 'lead',
            'title' => 'Новая заявка API '.$validated['form_lead'],
            'message' => $this->buildLeadNotificationMessage($lead),
            'url' => route('lids'),
            'payload' => [
                'lead_id' => $lead->id,
                'source' => 'api',
            ],
        ]);

        return response()->json(
            [
                'ok' => true,
                'lead_id' => $lead->id,
                'message' => 'Лид успешно создан.',
            ],
            201,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    // Собирает единый текст лида из API-полей.
    private function buildContent(array $validated): string
    {
        $lines = [
            'Телефон: '.trim((string) $validated['phone']),
        ];

        if (! empty($validated['email'])) {
            $lines[] = 'Почта: '.trim((string) $validated['email']);
        }

        if (! empty($validated['message'])) {
            $lines[] = 'Сообщение: '.trim((string) $validated['message']);
        }

        return implode("\n", $lines);
    }

    // Очищает контент лида перед сохранением.
    private function sanitizeLeadContent(?string $content): ?string
    {
        if ($content === null) {
            return null;
        }

        $content = str_replace(["\r\n", "\r"], "\n", $content);
        $content = strip_tags($content);
        $content = preg_replace('/[^\P{C}\n\t]+/u', '', $content) ?? '';
        $content = preg_replace("/\n{3,}/", "\n\n", $content) ?? '';
        $content = trim($content);

        if ($content === '') {
            return null;
        }

        return mb_substr($content, 0, 5000);
    }

    // Формирует короткое сообщение для центра уведомлений.
    private function buildLeadNotificationMessage(Lead $lead): string
    {
        $base = trim($lead->name) !== '' ? $lead->name : 'Без названия';
        $content = trim((string) $lead->content);

        if ($content === '') {
            return $base;
        }

        return $base.' — '.mb_substr($content, 0, 120);
    }
}
