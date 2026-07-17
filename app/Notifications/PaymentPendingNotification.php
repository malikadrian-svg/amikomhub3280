<?php

namespace App\Notifications;

use App\Models\Order;
use App\Notifications\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class PaymentPendingNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    
    // Automatically retry this job 3 times on failure
    public $tries = 3;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [WhatsAppChannel::class];
    }

    /**
     * Define the WhatsApp message.
     */
    public function toWhatsApp(object $notifiable): string
    {
        $amount = number_format($this->order->total_amount, 0, ',', '.');
        
        return "Halo *{$this->order->customer_name}*,\n\n"
             . "Terima kasih telah memesan tiket untuk event *{$this->order->event->title}*.\n\n"
             . "Total tagihan Anda adalah *Rp {$amount}*.\n"
             . "Silakan selesaikan pembayaran Anda sebelum batas waktu habis.\n\n"
             . "Jika Anda belum dialihkan ke halaman pembayaran, Anda bisa membayar melalui tautan berikut (atau kembali ke browser Anda).\n\n"
             . "Terima kasih,\nTim AmikomHub";
    }
}
