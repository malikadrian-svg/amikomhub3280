<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Notifications\PaymentPendingNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPaymentPendingNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(OrderCreated $event): void
    {
        // $event->order is the notifiable. But we might need to route it.
        // Let's notify the Order model directly if it uses Notifiable trait, 
        // or notify the User model related to the order.
        // Since we check for customer_phone in WhatsAppChannel, notifying the Order model is fine if it uses Notifiable.
        // Let's assume User is notified, and we pass order to it, or we notify the Order itself.
        // I will use Notification facade to send it anonymously to avoid modifying the Order model if Notifiable is missing.

        \Illuminate\Support\Facades\Notification::route('whatsapp', $event->order->customer_phone)
            ->notify(new PaymentPendingNotification($event->order));
    }
}
