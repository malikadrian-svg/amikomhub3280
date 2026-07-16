<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Transaction — a single payment attempt for an Order.
 *
 * One Order can have multiple Transaction records (e.g., user retries payment).
 * The `gateway_order_id` is what gets sent to Midtrans as their "order_id".
 * The `raw_response` stores the full notification payload for audit compliance.
 */
class Transaction extends Model
{
    protected $fillable = [
        'order_id',
        'gateway_order_id',
        'gateway_transaction_id',
        'payment_gateway',
        'payment_type',
        'snap_token',
        'amount',
        'status',
        'raw_response',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'       => 'integer',
            'raw_response' => 'array',
            'paid_at'      => 'datetime',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * The order this payment attempt belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // =========================================================================
    // Status Helpers
    // =========================================================================

    /**
     * Normalize Midtrans status strings to a paid boolean.
     */
    public function isPaid(): bool
    {
        return in_array(strtolower($this->status), [
            'success',
            'settlement',
            'capture',
        ]);
    }

    public function isPending(): bool
    {
        return strtolower($this->status) === 'pending';
    }

    public function isFailed(): bool
    {
        return in_array(strtolower($this->status), [
            'failure',
            'cancel',
            'deny',
            'expire',
        ]);
    }
}