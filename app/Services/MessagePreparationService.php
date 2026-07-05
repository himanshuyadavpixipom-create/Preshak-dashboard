<?php

namespace App\Services;

use App\Models\Reminder;
use App\Models\MessageTemplate;
use Exception;

class MessagePreparationService
{
    protected TemplateRenderingService $renderingService;

    public function __construct(TemplateRenderingService $renderingService)
    {
        $this->renderingService = $renderingService;
    }

    /**
     * Prepare a message payload by combining a reminder and a template.
     * If no template is provided, it falls back to the active default template for that channel and type.
     */
    public function preparePayload(Reminder $reminder, MessageTemplate $template = null, string $channel = 'whatsapp'): array
    {
        if (!$template) {
            $template = MessageTemplate::where('reminder_type', $reminder->type)
                ->where('channel', $channel)
                ->where('is_active', true)
                ->where('is_default', true)
                ->first();
                
            if (!$template) {
                throw new Exception("No active default template found for type '{$reminder->type}' and channel '{$channel}'.");
            }
        }

        $client = $reminder->client;
        if (!$client) {
            throw new Exception("Reminder {$reminder->id} has no associated client.");
        }

        $rendered = $this->renderingService->renderTemplate($template, $client);

        // Determine recipient based on channel
        $recipient = null;
        if ($channel === 'whatsapp' || $channel === 'sms') {
            $recipient = $channel === 'whatsapp' ? $client->whatsapp_number : $client->phone;
            // Fallback: use phone if whatsapp is missing and vice-versa
            $recipient = $recipient ?? ($channel === 'whatsapp' ? $client->phone : $client->whatsapp_number);
        } elseif ($channel === 'email') {
            $recipient = $client->email;
        }

        return [
            'reminder_id' => $reminder->id,
            'template_id' => $template->id,
            'channel'     => $template->channel,
            'recipient'   => $recipient,
            'subject'     => $rendered['subject'],
            'body'        => $rendered['body'],
            'metadata'    => [
                'event_date' => $reminder->event_date,
            ]
        ];
    }
}
