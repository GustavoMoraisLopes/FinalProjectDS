<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;

    protected $table = 'equipments';

    protected $fillable = [
        'name',
        'category_id',
        'serial_number',
        'location',
        'owner_institution',
        'status',
        'condition',
        'description',
        'image',
        'purchase_date',
        'purchase_price',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function currentReservation()
    {
        return $this->hasOne(Reservation::class)
            ->where('status', 'approved')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    // Acessórios: equipamentos que são acessórios deste
    public function accessories(): BelongsToMany
    {
        return $this->belongsToMany(
            Equipment::class,
            'equipment_accessories',
            'equipment_id',
            'accessory_id'
        )
            ->withPivot('default_quantity', 'notes')
            ->withTimestamps();
    }

    // Equipamentos para os quais este é acessório
    public function usedAsAccessoryFor(): BelongsToMany
    {
        return $this->belongsToMany(
            Equipment::class,
            'equipment_accessories',
            'accessory_id',
            'equipment_id'
        )
            ->withPivot('default_quantity', 'notes')
            ->withTimestamps();
    }

    public function reservationItems(): HasMany
    {
        return $this->hasMany(ReservationItem::class);
    }
}

