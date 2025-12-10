<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'equipment_id',
        'user_id',
        'start_date',
        'end_date',
        'purpose',
        'project',
        'status',
        'checked_out_at',
        'checked_in_at',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'checked_out_at' => 'datetime',
        'checked_in_at' => 'datetime',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
