<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class MailSettingsService
{
    public function apply(): void
    {
        Config::set('mail.default', $this->mailer());
        Config::set('mail.mailers.smtp.transport', $this->mailer());
        Config::set('mail.mailers.smtp.host', $this->setting('mail_host'));
        Config::set('mail.mailers.smtp.port', (int) ($this->setting('mail_port', '587') ?: 587));
        Config::set('mail.mailers.smtp.encryption', $this->nullIfEmpty($this->setting('mail_encryption')));
        Config::set('mail.mailers.smtp.username', $this->setting('mail_username'));
        Config::set('mail.mailers.smtp.password', $this->setting('mail_password'));
        Config::set('mail.from.address', $this->setting('mail_from_address', 'noreply@example.com'));
        Config::set('mail.from.name', $this->setting('mail_from_name', config('app.name')));

        Mail::purge();
    }

    public function isConfigured(): bool
    {
        return $this->setting('mail_host') !== ''
            && $this->setting('mail_port') !== ''
            && $this->setting('mail_from_address') !== ''
            && $this->notificationEmail() !== '';
    }

    public function notificationEmail(): string
    {
        return $this->setting('orders_notification_email');
    }

    private function mailer(): string
    {
        return $this->setting('mail_driver', 'smtp') ?: 'smtp';
    }

    private function setting(string $key, ?string $default = ''): string
    {
        return trim((string) Setting::getValue($key, $default));
    }

    private function nullIfEmpty(string $value): ?string
    {
        return $value === '' ? null : $value;
    }
}
