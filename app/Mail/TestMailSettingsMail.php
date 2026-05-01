<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMailSettingsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build(): self
    {
        return $this->subject('Тестовое письмо Larding CMS')
            ->view('emails.test-mail-settings');
    }
}
