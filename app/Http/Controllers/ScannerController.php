<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function index()
    {
        return view('scanner.index');
    }

    public function search(Request $request)
    {
        $search = $request->input('query');

        $equipment = Equipment::where('serial_number', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->first();

        if (!$equipment) {
            return response()->json(['error' => 'Equipamento não encontrado'], 404);
        }

        return response()->json([
            'id' => $equipment->id,
            'name' => $equipment->name,
            'serial_number' => $equipment->serial_number,
            'category' => $equipment->category->name,
            'status' => $equipment->status,
            'location' => $equipment->location,
        ]);
    }
}
