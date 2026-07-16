<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',        // Added: links transaction to authenticated user
        'order_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_price',
        'status',
        'snap_token',
    ];

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * The event this transaction is for.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * The user who owns this transaction (ticket owner).
     * Nullable: pre-SSO transactions have user_id = NULL.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // =========================================================================
    // Helper Methods
    // =========================================================================

    /**
     * Determine if this transaction has been paid successfully.
     */
    public function isPaid(): bool
    {
        return in_array(strtolower($this->status), ['success', 'settlement', 'capture']);
    }
}