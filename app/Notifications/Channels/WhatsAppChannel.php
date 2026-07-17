<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Contracts\WhatsAppProviderInterface;

class WhatsAppChannel
{
    protected $provider;

    public function __construct(WhatsAppProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (! method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $message = $notification->toWhatsApp($notifiable);

        if (empty($message)) {
            return;
        }

        // Determine phone number
        $to = $notifiable->routeNotificationFor('whatsapp', $notification);

        if (! $to && isset($notifiable->customer_phone)) {
            $to = $notifiable->customer_phone;
        } elseif (! $to && isset($notifiable->phone)) {
            $to = $notifiable->phone;
        }

        if (! $to) {
            return;
        }

        $response = $this->provider->sendMessage($to, $message);

        if (isset($notification->order)) {
            \App\Models\NotificationLog::create([
                'order_id' => $notification->order->id,
                'type' => $notification->type ?? class_basename($notification),
                'status' => $response ? 'sent' : 'failed',
                'provider_response' => is_array($response) ? $response : ['raw' => $response],
            ]);
        }
    }
}
