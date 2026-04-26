<?php

namespace App\View\Components;

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
        } else {
            $this->notifications = collect();
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
