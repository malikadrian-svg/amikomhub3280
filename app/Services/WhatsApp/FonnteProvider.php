<?php

namespace App\Services\WhatsApp;

use App\Contracts\WhatsAppProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteProvider implements WhatsAppProviderInterface
{
    protected $token;
    protected $url = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    public function sendMessage(string $phone, string $message)
    {
        if (empty($this->token)) {
            Log::warning('Fonnte token is not configured. WhatsApp message not sent.', [
                'phone' => $phone,
                'message' => $message,
            ]);
            return false;
        }

        // Sanitize phone number (remove spaces, hyphens, plus signs)
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert leading 0 to 62 (Indonesia country code)
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->url, [
                'target' => $cleanPhone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Fonnte API error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'phone' => $phone,
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Exception in FonnteProvider: ' . $e->getMessage(), [
                'phone' => $phone,
            ]);
            return false;
        }
    }
}
