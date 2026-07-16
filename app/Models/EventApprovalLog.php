<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * EventApprovalLog — immutable audit trail for event status changes.
 * No update_at column — records are never mutated after creation.
 */
class EventApprovalLog extends Model
{
    public const UPDATED_AT = null; // Immutable record

    protected $fillable = [
        'event_id',
        'action',
        'from_status',
        'to_status',
        'reason',
        'performed_by',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
