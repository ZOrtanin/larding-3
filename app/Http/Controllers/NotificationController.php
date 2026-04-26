<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Открывает уведомление, помечает его прочитанным и переводит пользователя по ссылке.
    public function open(Notification $notification): RedirectResponse
    {
        if (! $notification->is_read) {
            $notification->update([
                'is_read' => true,
            ]);
        }

        return redirect()->to($notification->url ?: route('dashboard'));
    }

    // Помечает все уведомления прочитанными и возвращает пользователя назад.
    public function readAll(Request $request): RedirectResponse
    {
        Notification::query()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back();
    }
}
