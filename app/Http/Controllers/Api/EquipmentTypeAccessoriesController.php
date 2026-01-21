<?php

namespace App\Http\Controllers\Api;

use App\Models\EquipmentType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EquipmentTypeAccessoriesController extends Controller
{
    /**
     * Get recommended accessories for an equipment type
     */
    public function getByType($typeId)
    {
        $type = EquipmentType::with('recommendedAccessories')->findOrFail($typeId);

        $accessories = $type->recommendedAccessories->map(function ($accessory) {
            return [
                'id' => $accessory->id,
                'name' => $accessory->name,
                'category' => $accessory->category->name,
                'serial_number' => $accessory->serial_number,
                'status' => $accessory->status,
                'default_quantity' => $accessory->pivot->default_quantity,
                'notes' => $accessory->pivot->notes,
            ];
        });

        return response()->json([
            'success' => true,
            'accessories' => $accessories,
        ]);
    }

    /**
     * Get recommended accessories for an equipment (by its type)
     */
    public function getByEquipment($equipmentId)
    {
        $equipment = \App\Models\Equipment::findOrFail($equipmentId);

        // Se o equipamento não tem tipo, retorna vazio
        if (!$equipment->equipment_type_id) {
            return response()->json([
                'success' => true,
                'accessories' => [],
            ]);
        }

        $type = EquipmentType::with('recommendedAccessories')
            ->findOrFail($equipment->equipment_type_id);

        $accessories = $type->recommendedAccessories->map(function ($accessory) {
            return [
                'id' => $accessory->id,
                'name' => $accessory->name,
                'category' => $accessory->category->name,
                'serial_number' => $accessory->serial_number,
                'status' => $accessory->status,
                'default_quantity' => $accessory->pivot->default_quantity,
                'notes' => $accessory->pivot->notes,
            ];
        });

        return response()->json([
            'success' => true,
            'accessories' => $accessories,
        ]);
    }
}
