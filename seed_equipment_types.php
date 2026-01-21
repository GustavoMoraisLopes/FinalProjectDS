<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Buscar uma câmara existente
$cameraEquipment = \App\Models\Equipment::where('name', 'like', '%Camera%')
    ->orWhere('name', 'like', '%Câmara%')
    ->first();

if ($cameraEquipment) {
    $cameraType = \App\Models\EquipmentType::where('name', 'Câmara de Foto')->first();
    if ($cameraType) {
        $cameraEquipment->update(['equipment_type_id' => $cameraType->id]);
        echo "✅ Câmara atualizada com tipo: Câmara de Foto\n";
    }
}

// Buscar cartões de memória e atualizar
$memoryCards = \App\Models\Equipment::where('name', 'like', '%cartão%')
    ->orWhere('name', 'like', '%memory%')
    ->get();

if ($memoryCards->isNotEmpty()) {
    $memoryType = \App\Models\EquipmentType::where('name', 'Cartão de Memória')->first();
    if ($memoryType) {
        foreach ($memoryCards as $card) {
            $card->update(['equipment_type_id' => $memoryType->id]);
        }
        echo "✅ " . $memoryCards->count() . " cartões de memória atualizados\n";
    }
}

// Buscar lentes e atualizar
$lenses = \App\Models\Equipment::where('name', 'like', '%lente%')
    ->orWhere('name', 'like', '%lens%')
    ->get();

if ($lenses->isNotEmpty()) {
    $lensType = \App\Models\EquipmentType::where('name', 'Lente')->first();
    if ($lensType) {
        foreach ($lenses as $lens) {
            $lens->update(['equipment_type_id' => $lensType->id]);
        }
        echo "✅ " . $lenses->count() . " lentes atualizadas\n";
    }
}

echo "\n✅ Equipamentos com tipos seedados com sucesso!\n";
