<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\DeliveryLog;
use App\Services\Providers\EmailProvider;
use App\Services\Providers\TwilioSmsProvider;
use App\Services\Providers\WhatsAppProvider;

class BroadcastController extends Controller
{
    public function create()
    {
        $groups = Group::orderBy('name')->get();
        return view('broadcasts.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'channels' => 'required|array|min:1',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string'
        ]);

        $group = Group::with('clients')->findOrFail($validated['group_id']);
        $clients = $group->clients;

        if ($clients->isEmpty()) {
            return redirect()->back()->with('error', 'The selected group has no clients.');
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($clients as $client) {
            foreach ($validated['channels'] as $channel) {
                try {
                    $recipient = null;
                    $provider = null;

                    if ($channel === 'email') {
                        $recipient = $client->email;
                        $provider = app(EmailProvider::class);
                    } elseif ($channel === 'sms') {
                        $recipient = $client->phone;
                        $provider = app(TwilioSmsProvider::class);
                    } elseif ($channel === 'whatsapp') {
                        $recipient = $client->whatsapp_number ?? $client->phone;
                        $provider = app(WhatsAppProvider::class);
                    }

                    if (!$recipient) {
                        $errorCount++;
                        continue;
                    }

                    $body = str_replace('{name}', $client->name, $validated['body']);
                    $subject = $validated['subject'] ? str_replace('{name}', $client->name, $validated['subject']) : null;

                    $result = $provider->send($recipient, $body, $subject);

                    if ($result['success']) {
                        DeliveryLog::create([
                            'channel' => $channel,
                            'provider' => get_class($provider),
                            'recipient' => $recipient,
                            'subject' => $subject,
                            'body' => $body,
                            'status' => 'sent',
                            'provider_message_id' => $result['message_id'],
                            'sent_at' => now(),
                        ]);
                        $successCount++;
                    } else {
                        DeliveryLog::create([
                            'channel' => $channel,
                            'provider' => get_class($provider),
                            'recipient' => $recipient,
                            'subject' => $subject,
                            'body' => $body,
                            'status' => 'failed',
                            'failure_reason' => $result['error'],
                        ]);
                        $errorCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                }
            }
        }

        return redirect()->route('broadcasts.create')->with('success', "Broadcast complete. Sent: $successCount, Failed/Skipped: $errorCount.");
    }
}
