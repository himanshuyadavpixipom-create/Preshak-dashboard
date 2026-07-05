<?php

namespace App\Contracts;

interface MessageProviderInterface
{
    /**
     * Send a message through the provider.
     *
     * @param string $recipient
     * @param string $body
     * @param string|null $subject
     * @return array Array containing keys: 'success' (bool), 'message_id' (string|null), 'error' (string|null)
     */
    public function send(string $recipient, string $body, ?string $subject = null): array;
}
