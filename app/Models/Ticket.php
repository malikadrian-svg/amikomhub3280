<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Ticket — a single digital ticket for one attendee.
 *
 * One OrderItem with quantity=3 generates 3 Ticket records.
 * Each has its own unique ticket_code for individual QR scanning.
 */
class Ticket extends Model
{
    protected $fillable = [
        'order_item_id',
        'user_id',
        'event_id',
        'ticket_type_id',
        'ticket_code',
        'qr_code',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    // =========================================================================
    // Status Helpers
    // =========================================================================

    public function isActive(): bool    { return $this->status === 'active'; }
    public function isUsed(): bool      { return $this->status === 'used'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }
    public function isExpired(): bool   { return $this->status === 'expired'; }

    /**
     * Mark this ticket as checked-in (used at the gate).
     */
    public function checkIn(User $staff): bool
    {
        if (! $this->isActive()) {
            return false;
        }

        $this->update([
            'status'        => 'used',
            'checked_in_at' => now(),
            'checked_in_by' => $staff->id,
        ]);

        return true;
    }
}
