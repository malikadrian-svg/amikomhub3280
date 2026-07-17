<?php

namespace App\Notifications;

use App\Models\Order;
use App\Notifications\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class AbandonedCartNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $type; // e.g., '30m', '6h', '24h'
    public $tries = 3;

    public function __construct(Order $order, string $type)
    {
        $this->order = $order;
        $this->type = $type;
    }

    public function via(object $notifiable): array
    {
        return [WhatsAppChannel::class];
    }

    public function toWhatsApp(object $notifiable): string
    {
        // This simulates a link back to Midtrans payment URL, assuming we saved the gateway_order_id somewhere.
        // In the current schema, we have Transaction model containing gateway_order_id.
        $transaction = $this->order->transactions()->latest()->first();
        $paymentLink = $transaction ? route('checkout.payment', $transaction->gateway_order_id) : route('home');

        $timeText = '';
        if ($this->type === '30m') {
            $timeText = "Sepertinya Anda lupa menyelesaikan pembayaran.";
        } elseif ($this->type === '6h') {
            $timeText = "Waktu pembayaran Anda hampir habis!";
        } else {
            $timeText = "Ini adalah pengingat terakhir sebelum pesanan Anda dibatalkan.";
        }

        return "Halo *{$this->order->customer_name}*,\n\n"
             . "{$timeText}\n\n"
             . "Pesanan Anda untuk event *{$this->order->event->title}* masih menunggu pembayaran.\n"
             . "Selesaikan segera agar tidak kehabisan tiket!\n\n"
             . "Lanjutkan pembayaran di sini:\n"
             . $paymentLink . "\n\n"
             . "Abaikan pesan ini jika Anda sudah membayar.\nTim AmikomHub";
    }
}
