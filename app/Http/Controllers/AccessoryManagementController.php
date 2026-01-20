<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentAccessory;
use App\Models\Category;
use Illuminate\Http\Request;

class AccessoryManagementController extends Controller
{
    /**
     * Show accessories configuration page
     */
    public function index()
    {
        $this->authorize('admin');

        $equipments = Equipment::with(['category', 'accessories'])->orderBy('name')->get();
        $categories = Category::all();

        return view('admin.accessories.index', compact('equipments', 'categories'));
    }

    /**
     * Attach accessories to equipment
     */
    public function attach(Request $request, Equipment $equipment)
    {
        $this->authorize('admin');

        $request->validate([
            'accessory_id' => 'required|exists:equipments,id|different:equipment',
            'default_quantity' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string|max:255',
        ]);

        // Evitar duplicatas
        $exists = EquipmentAccessory::where('equipment_id', $equipment->id)
            ->where('accessory_id', $request->accessory_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Este acessório já está associado a este equipamento.');
        }

        EquipmentAccessory::create([
            'equipment_id' => $equipment->id,
            'accessory_id' => $request->accessory_id,
            'default_quantity' => $request->default_quantity,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Acessório adicionado com sucesso!');
    }

    /**
     * Remove accessory from equipment
     */
    public function detach(Equipment $equipment, EquipmentAccessory $accessory)
    {
        $this->authorize('admin');

        if ($accessory->equipment_id !== $equipment->id) {
            abort(403);
        }

        $accessory->delete();

        return back()->with('success', 'Acessório removido com sucesso!');
    }

    /**
     * Update accessory details
     */
    public function update(Request $request, Equipment $equipment, EquipmentAccessory $accessory)
    {
        $this->authorize('admin');

        if ($accessory->equipment_id !== $equipment->id) {
            abort(403);
        }

        $request->validate([
            'default_quantity' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string|max:255',
        ]);

        $accessory->update($request->only(['default_quantity', 'notes']));

        return back()->with('success', 'Acessório atualizado com sucesso!');
    }
}
