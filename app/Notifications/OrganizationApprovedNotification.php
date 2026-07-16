<?php

namespace App\Notifications;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrganizationApprovedNotification extends Notification
{
    use Queueable;

    public $organization;

    /**
     * Create a new notification instance.
     */
    public function __construct(Organization $organization)
    {
        $this->organization = $organization;
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
        return [
            'title' => 'Pendaftaran Penyelenggara Disetujui',
            'message' => 'Selamat! Organisasi "' . $this->organization->name . '" telah disetujui. Anda sekarang dapat mulai membuat event.',
            'action_url' => route('organizer.dashboard', $this->organization->slug),
            'icon' => 'building',
            'organization_id' => $this->organization->id,
        ];
    }
}
