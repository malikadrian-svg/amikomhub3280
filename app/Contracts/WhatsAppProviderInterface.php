<?php

namespace App\Contracts;

interface WhatsAppProviderInterface
{
    /**
     * Send a WhatsApp message to a specific phone number.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message body.
     * @return array|bool True/Response array on success, false/Exception on failure.
     */
    public function sendMessage(string $phone, string $message);
}
