<?php

namespace App\Services;

use App\Models\Block;
use Illuminate\Support\Collection;

class PageBlockService
{
    // Загружает блоки страницы, обогащает служебными данными и сортирует по порядку.
    public function getSortedBlocks(): Collection
    {
        return Block::query()
            ->with('variables')
            ->get()
            ->map(function (Block $block) {
                $variables = $block->variables;
                $order = (int) optional($variables->firstWhere('name', 'order'))->default_value;
                $isVisible = optional($variables->firstWhere('name', 'visibility'))->default_value ?? '1';

                return [
                    'my_id' => $order,
                    'block_id' => $block->id,
                    'is_visible' => $isVisible === '1',
                    'data' => $block->blade_template,
                ];
            })
            ->sortBy('my_id')
            ->values();
    }
}
