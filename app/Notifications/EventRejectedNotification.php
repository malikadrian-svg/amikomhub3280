<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class EventRejectedNotification extends Notification
{
    use Queueable;

    public $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Load organization if not already loaded
        $organization = $this->event->organization ?? $this->event->load('organization')->organization;
        $orgSlug = $organization?->slug ?? 'unknown';

        return [
            'title'      => 'Event Ditolak',
            'message'    => 'Mohon maaf, event "' . $this->event->title . '" Anda ditolak oleh admin.',
            'action_url' => route('organizer.events.edit', [$orgSlug, $this->event]),
            'icon'       => 'x-circle',
            'event_id'   => $this->event->id,
        ];
    }
}
