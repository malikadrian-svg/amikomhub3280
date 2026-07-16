<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyRevenueSnapshot extends Model
{
    protected $fillable = [
        'organization_id',
        'date',
        'total_orders',
        'total_tickets',
        'gross_revenue',
        'commission_revenue',
        'net_revenue',
    ];

    protected function casts(): array
    {
        return [
            'date'               => 'date',
            'total_orders'       => 'integer',
            'total_tickets'      => 'integer',
            'gross_revenue'      => 'integer',
            'commission_revenue' => 'integer',
            'net_revenue'        => 'integer',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
