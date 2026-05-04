<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

class PageRenderService
{
    // Собирает HTML страницы из отсортированных блоков с учётом режима рендера.
    public function render(Collection $blocks, bool $isAdmin, array $data = []): string
    {
        $html = '';

        foreach ($blocks as $block) {
            if (! $isAdmin && ! $block['is_visible']) {
                continue;
            }

            $html .= $this->renderBlock($block, $isAdmin);
        }

        return Blade::render($html, $data);
    }

    // Рендерит один блок страницы и при необходимости добавляет админские элементы управления.
    private function renderBlock(array $block, bool $isAdmin): string
    {
        $blockId = (int) $block['block_id'];
        $isVisible = (bool) $block['is_visible'];
        $viewButtonClass = $isVisible ? 'bg-orange-500' : 'bg-gray-500';

        $html = '<div class="js-block-edit relative" data-block-id="'.$blockId.'" data-visible="'.($isVisible ? '1' : '0').'">';
        $html .= $this->replaceBlockPlaceholders((string) $block['data'], $blockId, $isAdmin);

        if ($isAdmin) {
            $html .= $this->renderAdminControls($blockId, $viewButtonClass);
        }

        if (! $isVisible) {
            $html .= '<div class="
                              absolute inset-0
                              bg-gray-400/30
                              backdrop-blur-sm
                              rounded
                             " style="z-index: 0; background-color: rgb(97, 94, 94); opacity: 0.7;"></div>';
        }

        $html .= '</div>';

        return $html;
    }

    // Подставляет служебные плейсхолдеры в шаблон блока.
    private function replaceBlockPlaceholders(string $html, int $blockId, bool $isAdmin): string
    {
        $html = str_replace('{{ $block_id }}', (string) $blockId, $html);
        $html = str_replace('{{ $site_url }}', config('app.url'), $html);
        $html = str_replace(['{{ admin_hide_start }}', '{{admin_hide_start}}'], '<!-- ADMIN_HIDE_START -->', $html);
        $html = str_replace(['{{ admin_hide_end }}', '{{admin_hide_end}}'], '<!-- ADMIN_HIDE_END -->', $html);

        if ($isAdmin) {
            $html = preg_replace('/<!--\s*ADMIN_HIDE_START\s*-->.*?<!--\s*ADMIN_HIDE_END\s*-->/si', '', $html) ?? $html;
        }

        $html = str_replace(['<!-- ADMIN_HIDE_START -->', '<!-- ADMIN_HIDE_END -->'], '', $html);

        // Принудительно устанавливаем display:block для блоков первого уровня при рендере с админскими правами
        if ($isAdmin) {
            // Используем регулярное выражение, чтобы заменить style="display:none;" только в внешних div-блоках
            $html = preg_replace_callback('/<div([^>]*)style="display:\s*none;?"([^>]*)>/i', function($matches) {
                return '<div' . $matches[1] . 'style="display:block;"' . $matches[2] . '>';
            }, $html);
        }

        return $html;
    }

    // Возвращает HTML админских кнопок управления блоком.
    private function renderAdminControls(int $blockId, string $viewButtonClass): string
    {
        return View::make('template.partials.block-admin-controls', [
            'blockId' => $blockId,
            'viewButtonClass' => $viewButtonClass,
        ])->render();
    }
}
