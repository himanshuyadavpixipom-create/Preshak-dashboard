<?php

namespace App\Services;

use App\Models\Client;
use App\Models\MessageTemplate;
use Illuminate\Support\Carbon;

class TemplateRenderingService
{
    /**
     * Centralized list of supported placeholders.
     * This acts as the source of truth for both rendering and the UI helper.
     */
    public static function getAvailablePlaceholders(): array
    {
        return [
            '{{client_name}}' => 'The full name of the client',
            '{{company_name}}' => 'The client\'s company name',
            '{{policy_name}}' => 'The name of the policy',
            '{{policy_number}}' => 'The policy number',
            '{{premium_due_date}}' => 'The premium due date (e.g., Oct 15, 2026)',
            '{{birthday}}' => 'The client\'s birthday (e.g., Jun 10)',
            '{{anniversary_date}}' => 'The client\'s anniversary date',
            '{{today_date}}' => 'Today\'s date',
        ];
    }

    /**
     * Replace placeholders in a given string using the provided client context.
     */
    public function renderText(string $text, Client $client): string
    {
        if (empty($text)) {
            return '';
        }

        $replacements = [
            '{{client_name}}' => $client->name ?? '',
            '{{company_name}}' => $client->company_name ?? '',
            '{{policy_name}}' => $client->policy_name ?? '',
            '{{policy_number}}' => $client->policy_number ?? '',
            '{{premium_due_date}}' => $client->premium_due_date ? Carbon::parse($client->premium_due_date)->format('M j, Y') : '',
            '{{birthday}}' => $client->birthday ? Carbon::parse($client->birthday)->format('M j') : '',
            '{{anniversary_date}}' => $client->anniversary_date ? Carbon::parse($client->anniversary_date)->format('M j') : '',
            '{{today_date}}' => now()->format('M j, Y'),
        ];

        return strtr($text, $replacements);
    }

    /**
     * Render the full template (subject and body).
     */
    public function renderTemplate(MessageTemplate $template, Client $client): array
    {
        return [
            'subject' => $template->subject ? $this->renderText($template->subject, $client) : null,
            'body' => $this->renderText($template->body, $client),
        ];
    }
}
