<?php

namespace App\Notifications;

use App\Models\Order;
use App\Notifications\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $tries = 3;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return [WhatsAppChannel::class];
    }

    public function toWhatsApp(object $notifiable): string
    {
        return "Halo *{$this->order->customer_name}*,\n\n"
             . "Hore! Pembayaran untuk pesanan *{$this->order->order_number}* telah berhasil dikonfirmasi.\n\n"
             . "E-Ticket untuk event *{$this->order->event->title}* sudah aktif di akun Anda.\n"
             . "Silakan periksa email Anda atau login ke akun AmikomHub Anda untuk melihat detail tiket.\n\n"
             . "Terima kasih telah menggunakan layanan kami!\nTim AmikomHub";
    }
}
