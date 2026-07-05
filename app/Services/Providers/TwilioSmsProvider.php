<?php

namespace App\Services\Providers;

use App\Contracts\MessageProviderInterface;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Exception;

class TwilioSmsProvider implements MessageProviderInterface
{
    public function send(string $recipient, string $body, ?string $subject = null): array
    {
        try {
            // Check if active
            $isActive = Setting::getValue('sms_active', true);
            if (!$isActive) {
                throw new Exception("SMS Integration is disabled in settings.");
            }

            $sid = Setting::getValue('twilio_sid', env('TWILIO_SID'));
            $token = Setting::getValue('twilio_auth_token', env('TWILIO_AUTH_TOKEN'));
            $from = Setting::getValue('twilio_from', env('TWILIO_FROM'));
            $simulate = Setting::getValue('sms_simulate', config('crm.simulate_sms_sending', true));

            $normalizedRecipient = $this->normalizePhoneNumber($recipient);

            // For local development or missing config, simulate success
            if ($simulate || empty($sid) || empty($token)) {
                Log::info("Simulated SMS via Twilio to {$normalizedRecipient}: {$body}");
                return [
                    'success' => true,
                    'message_id' => 'sim_sms_' . uniqid(),
                    'error' => null
                ];
            }

            // Real Twilio SDK logic
            $twilio = new \Twilio\Rest\Client($sid, $token);
            $message = $twilio->messages->create($normalizedRecipient, [
                'from' => $from,
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
