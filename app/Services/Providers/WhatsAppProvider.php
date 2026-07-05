<?php

namespace App\Services\Providers;

use App\Contracts\MessageProviderInterface;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Exception;

class WhatsAppProvider implements MessageProviderInterface
{
    public function send(string $recipient, string $body, ?string $subject = null): array
    {
        try {
            // Check if active
            $isActive = Setting::getValue('whatsapp_active', true);
            if (!$isActive) {
                throw new Exception("WhatsApp Integration is disabled in settings.");
            }

            $sid = Setting::getValue('twilio_sid', env('TWILIO_SID'));
            $token = Setting::getValue('twilio_auth_token', env('TWILIO_AUTH_TOKEN'));
            $from = Setting::getValue('twilio_whatsapp_from', env('TWILIO_WHATSAPP_FROM'));
            $simulate = Setting::getValue('whatsapp_simulate', config('crm.simulate_whatsapp_sending', true));

            $normalizedRecipient = $this->normalizePhoneNumber($recipient);

            // For local development or missing config, simulate success
            if ($simulate || empty($sid) || empty($token) || empty($from)) {
                Log::info("Simulated WhatsApp via Twilio to {$normalizedRecipient}: {$body}");
                return [
                    'success' => true,
                    'message_id' => 'sim_wa_' . uniqid(),
                    'error' => null
                ];
            }

            // Real Twilio SDK logic for WhatsApp
            $twilio = new \Twilio\Rest\Client($sid, $token);
            $message = $twilio->messages->create("whatsapp:" . $normalizedRecipient, [
                'from' => "whatsapp:" . $from,
                'body' => $body
            ]);

            return [
                'success' => true,
                'message_id' => $message->sid,
                'error' => null
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message_id' => null,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Normalize phone number to E.164 format.
     */
    protected function normalizePhoneNumber(string $number): string
    {
        // Remove all characters except digits and a leading '+'
        $normalized = preg_replace('/[^\d+]/', '', $number);
        
        // If it doesn't have a leading '+', add it (assuming country code is included)
        if (!str_starts_with($normalized, '+')) {
            $normalized = '+' . preg_replace('/[^\d]/', '', $normalized);
        }
        
        return $normalized;
    }
}
