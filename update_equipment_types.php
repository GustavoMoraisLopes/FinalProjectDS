<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 Atualizando equipamentos com tipos...\n\n";

// Buscar tipos
$cameraType = \App\Models\EquipmentType::where('name', 'Câmara de Foto')->first();
$lensType = \App\Models\EquipmentType::where('name', 'Lente')->first();
$memoryType = \App\Models\EquipmentType::where('name', 'Cartão de Memória')->first();
$batteryType = \App\Models\EquipmentType::where('name', 'Bateria')->first();

// Atualizar câmara de teste
$camera = \App\Models\Equipment::where('serial_number', 'CAM-TEST-001')->first();
if ($camera && $cameraType) {
    $camera->equipment_type_id = $cameraType->id;
    $camera->save();
    echo "✅ Câmara atualizada: {$camera->name} → {$cameraType->name}\n";
}

// Atualizar lente
$lens = \App\Models\Equipment::where('serial_number', 'LENS-TEST-001')->first();
if ($lens && $lensType) {
    $lens->equipment_type_id = $lensType->id;
    $lens->save();
    echo "✅ Lente atualizada: {$lens->name} → {$lensType->name}\n";
}

// Atualizar cartão
$memory = \App\Models\Equipment::where('serial_number', 'SD-TEST-001')->first();
if ($memory && $memoryType) {
    $memory->equipment_type_id = $memoryType->id;
    $memory->save();
    echo "✅ Cartão atualizado: {$memory->name} → {$memoryType->name}\n";
}

// Atualizar bateria
$battery = \App\Models\Equipment::where('serial_number', 'BAT-TEST-001')->first();
if ($battery && $batteryType) {
    $battery->equipment_type_id = $batteryType->id;
    $battery->save();
    echo "✅ Bateria atualizada: {$battery->name} → {$batteryType->name}\n";
}

// Atualizar outros equipamentos existentes
echo "\n🔧 Atualizando equipamentos existentes...\n";

// Canon EOS R5
$eq = \App\Models\Equipment::find(1);
if ($eq && $cameraType) {
    $eq->equipment_type_id = $cameraType->id;
    $eq->save();
    echo "✅ {$eq->name} → Câmara de Foto\n";
}

// Sony A7S III
$eq = \App\Models\Equipment::find(2);
if ($eq && $cameraType) {
    $eq->equipment_type_id = $cameraType->id;
    $eq->save();
    echo "✅ {$eq->name} → Câmara de Foto\n";
}

// Manfrotto Tripod
$tripodType = \App\Models\EquipmentType::where('name', 'Tripé')->first();
$eq = \App\Models\Equipment::find(3);
if ($eq && $tripodType) {
    $eq->equipment_type_id = $tripodType->id;
    $eq->save();
    echo "✅ {$eq->name} → Tripé\n";
}

// Rode VideoMic Pro
$micType = \App\Models\EquipmentType::where('name', 'Microfone')->first();
$eq = \App\Models\Equipment::find(4);
if ($eq && $micType) {
    $eq->equipment_type_id = $micType->id;
    $eq->save();
    echo "✅ {$eq->name} → Microfone\n";
}

echo "\n✅ Equipamentos atualizados com sucesso!\n";
