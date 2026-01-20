<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\JsonResponse;

class AccessoriesController extends Controller
{
    /**
     * Get accessories for a given equipment
     */
    public function getAccessories($equipmentId): JsonResponse
    {
        $equipment = Equipment::with('accessories')->findOrFail($equipmentId);

        $accessories = $equipment->accessories->map(function ($accessory) use ($equipment) {
            $pivot = $equipment->accessories()->wherePivot('accessory_id', $accessory->id)->first();

            return [
                'id' => $accessory->id,
                'name' => $accessory->name,
                'serial_number' => $accessory->serial_number,
                'category' => $accessory->category->name,
                'default_quantity' => $pivot->pivot->default_quantity ?? 1,
                'notes' => $pivot->pivot->notes,
                'status' => $accessory->status,
            ];
        });

        return response()->json([
            'success' => true,
            'accessories' => $accessories,
            'count' => $accessories->count(),
        ]);
    }

    /**
     * Check equipment availability
     */
    public function checkAvailability($equipmentId): JsonResponse
    {
        $equipment = Equipment::findOrFail($equipmentId);

        $isAvailable = $equipment->status === 'available' && !$equipment->currentReservation();

        return response()->json([
            'available' => $isAvailable,
            'status' => $equipment->status,
            'current_location' => $equipment->location,
        ]);
    }
}
