<?php

namespace App\Services\Providers;

use App\Contracts\MessageProviderInterface;
use App\Mail\ReminderEmail;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Exception;

class EmailProvider implements MessageProviderInterface
{
    public function send(string $recipient, string $body, ?string $subject = null, array $metadata = []): array
    {
        try {
            // Check settings or config to simulate or actually send
            $simulate = Setting::getValue('email_simulate', config('crm.simulate_email_sending', false));
            
            if ($simulate) {
                return [
                    'success' => true,
                    'message_id' => 'sim_email_' . uniqid(),
                    'error' => null
                ];
            }

            // Optional: Override mail config dynamically if DB settings exist
            $mailer = Setting::getValue('mail_mailer');
            if ($mailer) {
                Config::set('mail.default', $mailer);
                Config::set("mail.mailers.{$mailer}.host", Setting::getValue('mail_host', config("mail.mailers.{$mailer}.host")));
                Config::set("mail.mailers.{$mailer}.port", Setting::getValue('mail_port', config("mail.mailers.{$mailer}.port")));
                Config::set("mail.mailers.{$mailer}.username", Setting::getValue('mail_username', config("mail.mailers.{$mailer}.username")));
                
                $pass = Setting::getValue('mail_password');
                if ($pass) {
                    Config::set("mail.mailers.{$mailer}.password", $pass);
                }
                
                Config::set("mail.mailers.{$mailer}.encryption", Setting::getValue('mail_encryption', config("mail.mailers.{$mailer}.encryption")));
                Config::set('mail.from.address', Setting::getValue('mail_from_address', config('mail.from.address')));
                Config::set('mail.from.name', Setting::getValue('mail_from_name', config('mail.from.name')));
            }

            Mail::to($recipient)->send(new ReminderEmail($subject ?? 'Reminder', $body));

            return [
                'success' => true,
                'message_id' => null, // Mail driver rarely gives a sync message_id easily
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
}
