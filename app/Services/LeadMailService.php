<?php

namespace App\Services;

use App\Mail\NewLeadNotificationMail;
use App\Models\Lead;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LeadMailService
{
    public function __construct(
        private readonly MailSettingsService $mailSettingsService,
    ) {
    }

    public function sendNewLeadNotification(Lead $lead): void
    {
        if (! $this->mailSettingsService->isConfigured()) {
            Log::info('Lead email notification skipped because mail settings are incomplete.', [
                'lead_id' => $lead->id,
            ]);

            return;
        }

        try {
            $this->mailSettingsService->apply();
            $recipient = $this->mailSettingsService->notificationEmail();

            Log::info('Attempting to send new lead email notification.', [
                'lead_id' => $lead->id,
                'recipient' => $recipient,
            ]);

            Mail::to($recipient)
                ->send(new NewLeadNotificationMail($lead));

            Log::info('New lead email notification sent.', [
                'lead_id' => $lead->id,
                'recipient' => $recipient,
            ]);
        } catch (\Throwable $exception) {
            Log::warning('Failed to send new lead email notification.', [
                'lead_id' => $lead->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
