<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$camera = \App\Models\Equipment::where('serial_number', 'CAM-TEST-001')->first();

if ($camera) {
    echo "📷 Câmara encontrada:\n";
    echo "   ID: {$camera->id}\n";
    echo "   Nome: {$camera->name}\n";
    echo "   Type ID: {$camera->equipment_type_id}\n";

    if ($camera->equipment_type_id) {
        echo "   Tipo: {$camera->equipmentType->name}\n";

        // Testar API
        echo "\n🔍 Testando API:\n";
        $url = "/api/type-accessories/equipment/{$camera->id}";
        echo "   URL: {$url}\n";

        $type = \App\Models\EquipmentType::with('recommendedAccessories')->find($camera->equipment_type_id);
        if ($type) {
            $accessories = $type->recommendedAccessories;
            echo "   Acessórios encontrados: {$accessories->count()}\n";
            foreach ($accessories as $acc) {
                echo "      - {$acc->name} (qty: {$acc->pivot->default_quantity})\n";
            }
        }
    } else {
        echo "   ⚠️  Câmara não tem tipo configurado!\n";
    }
} else {
    echo "❌ Câmara não encontrada na BD\n";
}

// Listar TODOS os equipamentos
echo "\n📋 Todos os equipamentos:\n";
$allEquipments = \App\Models\Equipment::all();
foreach ($allEquipments as $eq) {
    $type = $eq->equipment_type_id ? " (Tipo: {$eq->equipmentType->name})" : " (SEM TIPO)";
    echo "   - [{$eq->id}] {$eq->name}{$type}\n";
}
