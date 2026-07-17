<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\EventTicketMail;

class SendPaymentSuccessEmail implements ShouldQueue
{
    use InteractsWithQueue;
    
    public $tries = 3;

    public function handle(OrderPaid $event): void
    {
        try {
            Mail::to($event->order->customer_email)->send(new EventTicketMail($event->order));
        } catch (\Exception $e) {
            Log::error('Failed to send EventTicketMail: ' . $e->getMessage(), [
                'order_id' => $event->order->id
            ]);
            
            // Re-throw so the queue knows it failed and can retry
            throw $e;
        }
    }
}
