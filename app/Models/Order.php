<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Traits\BelongsToOrganization;

/**
 * Order — a customer's purchase intent.
 *
 * Lifecycle: pending → paid → completed → (refunded | cancelled)
 * Each Order has:
 *  - one or more OrderItems (ticket_type + quantity)
 *  - one or more Transactions (payment attempts)
 *  - one OrderCommission (platform split, created on payment success)
 */
class Order extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'user_id',
        'event_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'subtotal',
        'platform_fee',
        'total_amount',
        'status',
        'notes',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal'      => 'integer',
            'platform_fee'  => 'integer',
            'total_amount'  => 'integer',
            'paid_at'       => 'datetime',
            'completed_at'  => 'datetime',
            'expired_at'    => 'datetime',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function commission(): HasOne
    {
        return $this->hasOne(OrderCommission::class);
    }

    // =========================================================================
    // Status Helpers
    // =========================================================================

    public function isPending(): bool    { return $this->status === 'pending'; }
    public function isPaid(): bool       { return $this->status === 'paid'; }
    public function isCompleted(): bool  { return $this->status === 'completed'; }
    public function isCancelled(): bool  { return $this->status === 'cancelled'; }
    public function isRefunded(): bool   { return $this->status === 'refunded'; }
    public function isExpired(): bool    { return $this->status === 'expired'; }

    public function isSuccessful(): bool
    {
        return in_array($this->status, ['paid', 'completed']);
    }

    /**
     * Total tickets across all order items.
     */
    public function totalTickets(): int
    {
        return $this->items->sum('quantity');
    }
}
