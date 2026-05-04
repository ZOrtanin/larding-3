<?php

namespace App\View\Components;

use App\Models\Block;
use App\Models\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public function __construct(
        public ?string $siteName = null,
        public ?string $siteDescription = null,
        public ?Collection $notifications = null,
        public ?Collection $editorBlocks = null,
        public int $unreadNotificationsCount = 0,
    ) {
        if (Auth::check()) {
            $this->notifications = Notification::query()
                ->where('is_read', false)
                ->latest()
                ->limit(3)
                ->get();

            $this->unreadNotificationsCount = Notification::query()
                ->where('is_read', false)
                ->count();

            $this->editorBlocks = Block::query()
                ->with('variables')
                ->get()
                ->map(function (Block $block) {
                    $order = (int) optional($block->variables->firstWhere('name', 'order'))->default_value;
                    $isVisible = (optional($block->variables->firstWhere('name', 'visibility'))->default_value ?? '1') === '1';

                    return [
                        'id' => $block->id,
                        'name' => $block->name ?: 'Без названия',
                        'description' => $block->description ?: '',
                        'order' => $order,
                        'is_visible' => $isVisible,
                    ];
                })
                ->sortBy([
                    ['order', 'asc'],
                    ['id', 'asc'],
                ])
                ->values();
        } else {
            $this->notifications = collect();
            $this->editorBlocks = collect();
        }
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
