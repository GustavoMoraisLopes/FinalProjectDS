<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::with('category');

        // Restrição por instituição (não-admin)
        $user = $request->user();
        if ($user && !$user->isAdmin()) {
            $query->where(function($q) use ($user) {
                $q->where('owner_institution', $user->institution)
                  ->orWhere('owner_institution', 'shared');
            });
        }

        // Filtros
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $equipments = $query->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('equipments.index', compact('equipments', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('equipments.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'serial_number' => 'required|string|unique:equipments',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:available,maintenance,loaned,unavailable',
            'condition' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric',
        ]);

        Equipment::create($validated);

        return redirect()->route('equipments.index')->with('success', 'Equipamento adicionado com sucesso!');
    }

    public function show(Equipment $equipment)
    {
        $equipment->load('category', 'reservations.user');
        return view('equipments.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        $categories = Category::all();
        return view('equipments.edit', compact('equipment', 'categories'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'serial_number' => 'required|string|unique:equipments,serial_number,' . $equipment->id,
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:available,maintenance,loaned,unavailable',
            'condition' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Remover imagem se solicitado
        if ($request->has('remove_image')) {
            if ($equipment->image && Storage::disk('public')->exists($equipment->image)) {
                Storage::disk('public')->delete($equipment->image);
            }
            $equipment->update(['image' => null]);
            return redirect()->route('equipments.edit', $equipment)->with('success', 'Foto removida com sucesso!');
        }

        // Upload imagem se fornecido
        if ($request->hasFile('image')) {
            if ($equipment->image && Storage::disk('public')->exists($equipment->image)) {
                Storage::disk('public')->delete($equipment->image);
            }
            $validated['image'] = $request->file('image')->store('equipments', 'public');
        }

        $equipment->update($validated);

        return redirect()->route('equipments.index')->with('success', 'Equipamento atualizado com sucesso!');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('equipments.index')->with('success', 'Equipamento removido com sucesso!');
    }
}
