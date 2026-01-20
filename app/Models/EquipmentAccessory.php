<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EquipmentAccessory extends Model
{
    protected $table = 'equipment_accessories';

    protected $fillable = [
        'equipment_id',
        'accessory_id',
        'default_quantity',
        'notes',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    public function accessory(): BelongsTo
    {
        return $this->belongsTo(Equipment::class, 'accessory_id');
    }
}
