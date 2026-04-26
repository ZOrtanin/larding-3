<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SettingsController extends Controller
{
    // Ключи настроек сайта, которые сохраняются в таблицу settings.
    private const SETTINGS_KEYS = [
        'site_name',
        'site_description',
        'site_logo',
        'validation_rules',
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
    ];

    // Открывает страницу настроек и подставляет сохранённые значения.
    public function edit(Request $request): View {
        $settings = $this->loadSettings();

        return view('settings.edit', [
            'user' => $request->user(),
            'settings' => $settings,
        ]);
    }

    // Сохраняет общие настройки сайта и почты из формы админки.
    public function update(Request $request): RedirectResponse {
        $update_settings_name = '';

        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:1000'],
            'site_logo' => ['nullable', 'string', 'max:2048'],
            'validation_rules' => ['nullable', 'string', 'max:10000'],
            'mail_driver' => ['nullable', 'string', 'max:255'],
            'mail_host' => ['nullable', 'string', 'max:255'],
            'mail_port' => ['nullable', 'string', 'max:20'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_encryption' => ['nullable', 'string', 'max:255'],
        ]);



        foreach (self::SETTINGS_KEYS as $key) {
            
            if($request[$key]!== null){
                Setting::setValue($key, $validated[$key] ?? null);
                $update_settings_name = explode('_', $key)[0];
            }
            
        }

        
        return Redirect::route('settings.edit')->with('status', 'settings-updated-'.$update_settings_name);
       
        
    }

    // Загружает настройки сайта из базы и дополняет их значениями по умолчанию.
    private function loadSettings(): array {
        $storedSettings = Setting::query()
            ->whereIn('key', self::SETTINGS_KEYS)
            ->pluck('value', 'key');

        $defaults = [
            'site_name' => 'Супер сайт',
            'site_description' => '',
            'site_logo' => '',
            'validation_rules' => '',
            'mail_driver' => '',
            'mail_host' => '',
            'mail_port' => '',
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => '',
        ];

        foreach ($defaults as $key => $value) {
            $defaults[$key] = $storedSettings[$key] ?? $value;
        }

        return $defaults;
    }
}
