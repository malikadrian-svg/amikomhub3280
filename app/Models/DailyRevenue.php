<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyRevenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'organization_id',
        'gross_revenue',
        'platform_fee',
        'commission_amount',
        'net_revenue',
        'tickets_sold',
    ];

    protected $casts = [
        'date' => 'date',
        'gross_revenue' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'net_revenue' => 'decimal:2',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
