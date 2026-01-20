<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    protected $fillable = [
        'equipment_id',
        'user_id',
        'start_date',
        'end_date',
        'pickup_time',
        'return_time',
        'school',
        'course_type',
        'course_name',
        'class_year',
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
        'pickup_time' => 'datetime:H:i',
        'return_time' => 'datetime:H:i',
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

    public function items(): HasMany
    {
        return $this->hasMany(ReservationItem::class);
    }

    // Obter todos os equipamentos desta requisição (principal + acessórios)
    public function allEquipments()
    {
        return $this->items()->with('equipment')->get();
    }
}
