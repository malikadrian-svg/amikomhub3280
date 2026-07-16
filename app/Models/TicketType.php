<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * TicketType — pricing tier for an event.
 *
 * Replaces the single price/stock columns that used to live on events.
 * An event can have multiple TicketTypes (Regular, VIP, Early Bird, etc.).
 */
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketType extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'quantity',
        'max_per_order',
        'sale_start',
        'sale_end',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price'         => 'integer',
            'quantity'      => 'integer',
            'quantity_sold' => 'integer',
            'is_active'     => 'boolean',
            'sale_start'    => 'datetime',
            'sale_end'      => 'datetime',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // =========================================================================
    // Availability Helpers
    // =========================================================================

    /**
     * Remaining tickets available for purchase.
     */
    public function remaining(): int
    {
        return max(0, $this->quantity - $this->quantity_sold);
    }

    /**
     * Is this ticket type available right now?
     */
    public function isAvailable(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->remaining() <= 0) {
            return false;
        }

        $now = now();

        if ($this->sale_start && $now->lt($this->sale_start)) {
            return false;
        }

        if ($this->sale_end && $now->gt($this->sale_end)) {
            return false;
        }

        return true;
    }

    /**
     * Is this ticket type sold out?
     */
    public function isSoldOut(): bool
    {
        return $this->remaining() <= 0;
    }
}
