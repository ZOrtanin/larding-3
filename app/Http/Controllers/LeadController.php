<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\Setting;
use App\Services\LeadMailService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function __construct(
        private readonly LeadMailService $leadMailService,
    ) {
    }

    private const ALLOWED_VALIDATION_RULES = [
        'required',
        'nullable',
        'string',
        'email',
        'numeric',
        'integer',
        'digits',
        'digits_between',
        'min',
        'max',
        'regex',
    ];

    private const STATUSES = [
        'new' => 'Новая',
        'in_progress' => 'В работе',
        'done' => 'Завершена',
        'spam' => 'Спам',
    ];

    public function index(): View {
        $leadsQuery = Lead::query();

        if (request()->user()?->role?->slug === 'manager') {
            $leadsQuery->where('status', '!=', 'done');
        }

        $leads = $leadsQuery
            ->latest()
            ->paginate(20, [
                'id',
                'name',
                'content',
                'status',
                'comments',
                'created_at',
                'updated_at',
            ]);

        return view('lids', [
            'leads' => $leads,
            'leadStatuses' => self::STATUSES,
        ]);
    }   
    
    public function store(Request $request): JsonResponse|RedirectResponse {        

        $arr_validation = [
            'name_form' => ['required', 'string', 'max:255'], 
            'block_id'  => ['required', 'string', 'max:50'],        
            'content' => ['required', 'string'],
        ];

        // проверка на число для безопасности
        if (is_numeric($request['block_id']) && Block::query()->find($request['block_id'])) {
            $id_block = $request['block_id'];
        }else{
            $id_block = 0;
            $non_block = $this->sanitizeLeadContent($request['block_id'] ?? null);
            $request['name_form'] = 'Не учтенная форма '.$non_block ;
        } 

        // получаем обязательные поля из доп настроек блока
        $requiredFieldsValue = Block::query()
            ->with('variables')
            ->find($id_block)
            ?->variables
            ->firstWhere('name', 'requiredFields')
            ?->default_value;

        $requiredFields = collect(explode(',', (string) $requiredFieldsValue))
            ->map(function ($field) {
                return trim($field);
            })
            ->filter()
            ->values()
            ->all();

        // правила валидации из настроек
        $rulesvalidation = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'digits_between:7,25'],
            'job' => ['required', 'string', 'max:255'],
        ];

        $settingsRules = $this->loadValidationRulesFromSettings();
        foreach ($settingsRules as $field => $rules) {
            if (! isset($rulesvalidation[$field])) {
                $rulesvalidation[$field] = $rules;
            }
        }

        foreach ($requiredFields as $field) { 
            if (isset($rulesvalidation[$field])) {
                $arr_validation[$field] = $rulesvalidation[$field];
            }
        }

        $validated = $request->validate($arr_validation);       

        $safeContent = $this->sanitizeLeadContent($validated['content'] ?? null);

        $lead = $this->createLeadRecord(
            name: $validated['name_form'],
            content: $safeContent,
            status: $validated['status'] ?? 'new',
            comments: $validated['comments'] ?? null,
        );

        $this->notifyAboutLead($lead, 'Новая заявка');

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'lead_id' => $lead->id,
            ], 201);
        }
        return '123';
    }

    public function trackOutbound(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'to' => ['required', 'url', 'max:2048'],
            'label' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
        ]);

        $targetUrl = $validated['to'];

        if (! $this->isExternalUrl($targetUrl)) {
            throw new HttpResponseException(
                redirect()->route('template.index')->with('cms_lead_error', 'Разрешены только внешние ссылки.')
            );
        }

        $label = trim((string) ($validated['label'] ?? ''));
        $source = trim((string) ($validated['source'] ?? ''));

        $contentLines = [
            'Тип: переход по ссылке',
            'Ссылка: '.$targetUrl,
            'Страница: '.$request->headers->get('referer', $request->fullUrl()),
        ];

        if ($label !== '') {
            $contentLines[] = 'Метка: '.$label;
        }

        if ($source !== '') {
            $contentLines[] = 'Источник: '.$source;
        }

        $visitorId = trim((string) $request->cookie('visitor_id', ''));
        if ($visitorId !== '') {
            $contentLines[] = 'Visitor ID: '.$visitorId;
        }

        if ($request->ip()) {
            $contentLines[] = 'IP: '.$request->ip();
        }

        if ($request->userAgent()) {
            $contentLines[] = 'User-Agent: '.$request->userAgent();
        }

        $lead = $this->createLeadRecord(
            name: $label !== '' ? 'Переход по ссылке: '.$label : 'Переход по внешней ссылке',
            content: implode("\n", $contentLines),
            status: 'new',
            comments: null,
        );

        $this->notifyAboutLead($lead, 'Переход по ссылке');

        return redirect()->away($targetUrl);
    }

    public function updateStatus(Request $request, Lead $lead): RedirectResponse {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', array_keys(self::STATUSES))],
            'comment' => ['nullable', 'string'],
        ]);

        $authorName = (string) optional($request->user())->name;
        $commentLog = $this->appendComment(
            $lead->comments,
            trim((string) ($validated['comment'] ?? '')),
            $authorName
        );
        $statusLog = $this->appendComment(
            $commentLog,
            $this->buildStatusChangeComment($lead->status, $validated['status']),
            $authorName
        );

        $lead->update([
            'status' => $validated['status'],
            'comments' => $statusLog,
        ]);

        return redirect()->back();
    }

    public function destroy(Request $request, Lead $lead): JsonResponse {
        if ($request->user()?->role?->slug === 'manager') {
            abort(403);
        }

        $leadId = $lead->id;
        $lead->delete();

        return response()->json([
            'ok' => true,
            'deleted' => true,
            'lead_id' => $leadId,
        ]);
    }

    private function buildStatusChangeComment(string $fromStatus, string $toStatus): string {
        if ($fromStatus === $toStatus) {
            return '';
        }

        $fromLabel = self::STATUSES[$fromStatus] ?? $fromStatus;
        $toLabel = self::STATUSES[$toStatus] ?? $toStatus;

        return $fromLabel.' -> '.$toLabel;
    }

    private function appendComment(?string $existingComments, string $comment, string $authorName): ?string {
        if ($comment === '') {
            return $existingComments;
        }

        $author = $authorName !== '' ? $authorName : 'Неизвестный пользователь';
        $timestamp = now()->format('d.m.Y H:i:s');
        $commentEntry = implode(' | ', [$author, $timestamp, $comment]);

        if (! $existingComments) {
            return $commentEntry;
        }

        return $existingComments."\n\n".$commentEntry;
    }

    private function sanitizeLeadContent(?string $content): ?string {
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

    private function buildLeadNotificationMessage(Lead $lead): string {
        $base = trim($lead->name) !== '' ? $lead->name : 'Без названия';
        $content = trim((string) $lead->content);

        if ($content === '') {
            return $base;
        }

        return $base.' — '.mb_substr($content, 0, 120);
    }

    private function createLeadRecord(string $name, ?string $content, string $status = 'new', ?string $comments = null): Lead
    {
        return Lead::query()->create([
            'name' => $name,
            'content' => $content,
            'status' => $status,
            'comments' => $comments,
        ]);
    }

    private function notifyAboutLead(Lead $lead, string $title = 'Новая заявка'): void
    {
        Notification::query()->create([
            'type' => 'lead',
            'title' => $title,
            'message' => $this->buildLeadNotificationMessage($lead),
            'url' => route('lids'),
            'payload' => [
                'lead_id' => $lead->id,
            ],
        ]);

        $this->leadMailService->sendNewLeadNotification($lead);
    }

    private function isExternalUrl(string $url): bool
    {
        $targetHost = parse_url($url, PHP_URL_HOST);
        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);

        if (! is_string($targetHost) || $targetHost === '') {
            return false;
        }

        if (! is_string($appHost) || $appHost === '') {
            return true;
        }

        return mb_strtolower($targetHost) !== mb_strtolower($appHost);
    }

    private function loadValidationRulesFromSettings(): array {
        $rawRules = (string) Setting::getValue('site_validation_rules', '');

        if (trim($rawRules) === '') {
            return [];
        }

        $rules = [];
        $lines = preg_split('/\r\n|\r|\n/', $rawRules) ?: [];

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            $parts = array_map('trim', explode('|', $line));
            $field = array_shift($parts);

            if (! $this->isAllowedValidationField($field) || $parts === []) {
                continue;
            }

            $parsedRules = [];
            foreach ($parts as $rule) {
                $parsedRule = $this->parseValidationRule($rule);

                if ($parsedRule !== null) {
                    $parsedRules[] = $parsedRule;
                }
            }

            if ($parsedRules !== []) {
                $rules[$field] = array_values(array_unique($parsedRules));
            }
        }

        return $rules;
    }

    private function parseValidationRule(string $rule): ?string {
        $rule = trim($rule);
        if ($rule === '') {
            return null;
        }

        [$ruleName] = explode(':', $rule, 2);
        $ruleName = trim($ruleName);

        if (! in_array($ruleName, self::ALLOWED_VALIDATION_RULES, true)) {
            return null;
        }

        if (str_contains($rule, "\n") || str_contains($rule, "\r")) {
            return null;
        }

        return $rule;
    }

    private function isAllowedValidationField(?string $field): bool {
        if (! is_string($field) || $field === '') {
            return false;
        }

        return (bool) preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $field);
    }

}
