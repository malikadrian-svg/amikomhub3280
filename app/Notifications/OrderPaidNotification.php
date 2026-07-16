<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPaidNotification extends Notification
{
    use Queueable;

    public $order;

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
        return ['database']; // We stick to in-app database notifications for now
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pesanan Baru Berhasil',
            'message' => 'Tiket untuk event "' . $this->order->event->title . '" berhasil terjual sebesar Rp ' . number_format($this->order->total_amount, 0, ',', '.'),
            'action_url' => route('organizer.dashboard'), // Change to orders view later if exists
            'icon' => 'ticket',
            'order_id' => $this->order->id,
        ];
    }
}
