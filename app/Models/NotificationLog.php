<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'order_id',
        'type',
        'status',
        'provider_response',
    ];

    protected function casts(): array
    {
        return [
            'provider_response' => 'array',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
