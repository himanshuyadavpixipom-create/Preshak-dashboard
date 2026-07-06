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
            $templates = \App\Models\MessageTemplate::where('reminder_type', $reminder->type)
                ->where('is_active', true)
                ->where('is_default', true)
                ->get();

            if ($templates->isEmpty()) {
                throw new Exception("No active default templates found for reminder type '{$reminder->type}'.");
            }

            $successCount = 0;
            $errors = [];

            foreach ($templates as $template) {
                try {
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
                        throw new Exception("Recipient address/number is missing for channel {$channel}.");
                    }

                    // Execute send
                    $result = $provider->send(
                        $payload['recipient'], 
                        $payload['body'], 
                        $payload['subject'], 
                        $payload['metadata'] ?? []
                    );

                    if ($result['success']) {
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
                        $successCount++;
                    } else {
                        $this->handleFailure($reminder, $channel, get_class($provider), $payload, $result['error'], false);
                        $errors[] = "{$channel}: {$result['error']}";
                    }
                } catch (Exception $e) {
                    $this->handleFailure($reminder, $template->channel, 'unknown', [], $e->getMessage(), false);
                    $errors[] = "{$template->channel}: {$e->getMessage()}";
                }
            }

            if ($successCount > 0) {
                $reminder->update([
                    'status' => Reminder::STATUS_SENT,
                    'sent_at' => now()
                ]);
            } else {
                $reminder->update([
                    'status' => Reminder::STATUS_FAILED,
                ]);
                throw new Exception("All channels failed to send: " . implode(' | ', $errors));
            }

        } catch (Exception $e) {
            // If preparation or sending throws a hard exception
            // We use default updateReminderStatus = true here if it wasn't handled in the loop
            if ($reminder->status !== Reminder::STATUS_FAILED && $reminder->status !== Reminder::STATUS_SENT) {
                $this->handleFailure($reminder, 'unknown', 'unknown', [], $e->getMessage());
            }
            throw $e; // Re-throw to let the queue worker know it failed (triggering retries)
        }
    }

    protected function handleFailure(Reminder $reminder, string $channel, string $provider, array $payload, string $error, bool $updateReminderStatus = true)
    {
        if ($updateReminderStatus) {
            $reminder->update([
                'status' => Reminder::STATUS_FAILED,
            ]);
        }

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
