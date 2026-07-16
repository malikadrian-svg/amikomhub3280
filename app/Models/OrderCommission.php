<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\BelongsToOrganization;

class OrderCommission extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'order_id',
        'organization_id',
        'gross_amount',
        'commission_rate',
        'commission_amount',
        'organizer_amount',
        'settlement_status',
        'settled_at',
    ];

    protected function casts(): array
    {
        return [
            'gross_amount'      => 'integer',
            'commission_rate'   => 'float',
            'commission_amount' => 'integer',
            'organizer_amount'  => 'integer',
            'settled_at'        => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function isPending(): bool  { return $this->settlement_status === 'pending'; }
    public function isSettled(): bool  { return $this->settlement_status === 'settled'; }
    public function isPaidOut(): bool  { return $this->settlement_status === 'paid_out'; }
}
