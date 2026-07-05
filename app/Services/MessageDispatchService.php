<?php

namespace App\Services;

use App\Models\Reminder;
use App\Models\DeliveryLog;
use App\Services\Providers\EmailProvider;
use App\Services\Providers\TwilioSmsProvider;
use App\Services\Providers\WhatsAppProvider;
use Exception;

class MessageDispatchService
{
    protected MessagePreparationService $prepService;

    public function __construct(MessagePreparationService $prepService)
    {
        $this->prepService = $prepService;
    }

    /**
     * Dispatch the message for a given reminder.
     */
    public function dispatch(Reminder $reminder)
    {
        // Re-check state to prevent sending if it's already sent or cancelled
        if ($reminder->status !== Reminder::STATUS_PROCESSING && $reminder->status !== Reminder::STATUS_PENDING) {
            throw new Exception("Reminder {$reminder->id} is not in a valid state for sending (Current: {$reminder->status}).");
        }

        try {
            // Prepare the payload (resolves default template and renders variables)
            // By default, let's assume 'whatsapp' if we don't have a specific channel preference on the reminder.
            // In a more complex setup, you'd loop through preferred channels. For this CRM, let's attempt WhatsApp first.
            // To be completely robust, we could have the Job pass the desired channel.
            // Here, we try to prepare WhatsApp.
            // If the user wants specific channels per reminder type, we could query the templates.
            // We will prepare whatever channel the default template supports. We can just query the first active default template for the reminder type.
            
            $template = \App\Models\MessageTemplate::where('reminder_type', $reminder->type)
                ->where('is_active', true)
                ->where('is_default', true)
                ->first();

            if (!$template) {
                throw new Exception("No active default template found for reminder type '{$reminder->type}'.");
            }

            $payload = $this->prepService->preparePayload($reminder, $template, $template->channel);
            $channel = $payload['channel'];

            // Resolve the correct provider
            $provider = match ($channel) {
                'email' => app(EmailProvider::class),
                'sms' => app(TwilioSmsProvider::class),
                'whatsapp' => app(WhatsAppProvider::class),
                default => throw new Exception("Unknown channel '{$channel}'"),
            };

            if (empty($payload['recipient'])) {
                throw new Exception("Recipient address/number is missing.");
            }

            // Execute send
            $result = $provider->send($payload['recipient'], $payload['body'], $payload['subject']);

            if ($result['success']) {
                $reminder->update([
                    'status' => Reminder::STATUS_SENT,
                    'sent_at' => now()
                ]);

                DeliveryLog::create([
                    'reminder_id' => $reminder->id,
                    'template_id' => $template->id,
                    'channel' => $channel,
                    'provider' => get_class($provider),
                    'recipient' => $payload['recipient'],
                    'subject' => $payload['subject'],
                    'body' => $payload['body'],
                    'status' => 'sent',
                    'provider_message_id' => $result['message_id'],
                    'sent_at' => now(),
                ]);
            } else {
                $this->handleFailure($reminder, $channel, get_class($provider), $payload, $result['error']);
            }

        } catch (Exception $e) {
            // If preparation or sending throws a hard exception
            $this->handleFailure($reminder, 'unknown', 'unknown', [], $e->getMessage());
            throw $e; // Re-throw to let the queue worker know it failed (triggering retries)
        }
    }

    protected function handleFailure(Reminder $reminder, string $channel, string $provider, array $payload, string $error)
    {
        $reminder->update([
            'status' => Reminder::STATUS_FAILED,
        ]);

        DeliveryLog::create([
            'reminder_id' => $reminder->id,
            'template_id' => $payload['template_id'] ?? null,
            'channel' => $channel,
            'provider' => $provider,
            'recipient' => $payload['recipient'] ?? 'unknown',
            'subject' => $payload['subject'] ?? null,
            'body' => $payload['body'] ?? 'unknown',
            'status' => 'failed',
            'failure_reason' => $error,
        ]);
    }
}
