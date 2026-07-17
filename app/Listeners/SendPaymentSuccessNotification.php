<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Notifications\PaymentSuccessNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPaymentSuccessNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(OrderPaid $event): void
    {
        \Illuminate\Support\Facades\Notification::route('whatsapp', $event->order->customer_phone)
            ->notify(new PaymentSuccessNotification($event->order));
    }
}
