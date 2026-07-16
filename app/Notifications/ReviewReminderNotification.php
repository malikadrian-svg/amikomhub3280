<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReviewReminderNotification extends Notification
{
    use Queueable;

    /**
     * The event for which the review reminder is sent.
     */
    public function __construct(public readonly Event $event) {}

    /**
     * Deliver only as a database notification (in-app channel).
     * Email can be added as a second channel here in the future
     * without changing any other code.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data stored in the notifications table.
     * Retrievable from the user via $user->notifications.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type'        => 'review_reminder',
            'event_id'    => $this->event->id,
            'event_title' => $this->event->title,
            'event_date'  => $this->event->date->toDateString(),
            'message'     => "Bagaimana pengalaman Anda di \"{$this->event->title}\"? Tulis ulasan Anda sekarang!",
            'url'         => route('events.show', $this->event),
        ];
    }
}
