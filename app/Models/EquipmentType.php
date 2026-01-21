<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EquipmentType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
    ];

    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    // Acessórios recomendados para este tipo de equipamento
    public function recommendedAccessories(): BelongsToMany
    {
        return $this->belongsToMany(
            Equipment::class,
            'equipment_type_accessories',
            'equipment_type_id',
            'accessory_id'
        )
            ->withPivot('default_quantity', 'notes')
            ->withTimestamps();
    }
}
