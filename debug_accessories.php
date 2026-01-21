<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUGGING ACESSÓRIOS RECOMENDADOS ===\n\n";

// 1. Verificar se existem tipos de equipamento
$types = \App\Models\EquipmentType::all();
echo "📦 Tipos de Equipamento: " . $types->count() . "\n";
foreach ($types as $type) {
    echo "   - {$type->name}\n";
}

// 2. Verificar equipamentos com tipo
echo "\n🔧 Equipamentos com Tipo:\n";
$equipmentsWithType = \App\Models\Equipment::whereNotNull('equipment_type_id')->with('equipmentType')->get();
echo "   Total: " . $equipmentsWithType->count() . "\n";
foreach ($equipmentsWithType as $eq) {
    echo "   - {$eq->name} ({$eq->equipmentType->name})\n";
}

// 3. Verificar acessórios recomendados configurados
echo "\n✨ Acessórios Recomendados Configurados:\n";
$typeAccessories = \DB::table('equipment_type_accessories')->count();
echo "   Total de configurações: {$typeAccessories}\n";

if ($typeAccessories == 0) {
    echo "\n⚠️  PROBLEMA: Não há acessórios recomendados configurados!\n";
    echo "   Vou criar alguns agora...\n\n";

    // Criar alguns equipamentos de exemplo se não existirem
    $category = \App\Models\Category::first();

    if (!$category) {
        echo "❌ Erro: Não há categorias na base de dados!\n";
        exit;
    }

    // Criar tipos
    $cameraType = \App\Models\EquipmentType::where('name', 'Câmara de Foto')->first();
    $lensType = \App\Models\EquipmentType::where('name', 'Lente')->first();
    $memoryType = \App\Models\EquipmentType::where('name', 'Cartão de Memória')->first();
    $batteryType = \App\Models\EquipmentType::where('name', 'Bateria')->first();

    // Criar equipamentos de exemplo
    $camera = \App\Models\Equipment::firstOrCreate(
        ['serial_number' => 'CAM-TEST-001'],
        [
            'name' => 'Canon EOS 5D Mark IV (Teste)',
            'category_id' => $category->id,
            'equipment_type_id' => $cameraType->id,
            'status' => 'available',
        ]
    );
    echo "   ✓ Câmara criada: {$camera->name}\n";

    $lens = \App\Models\Equipment::firstOrCreate(
        ['serial_number' => 'LENS-TEST-001'],
        [
            'name' => 'Lente Canon EF 50mm f/1.8 (Teste)',
            'category_id' => $category->id,
            'equipment_type_id' => $lensType->id,
            'status' => 'available',
        ]
    );
    echo "   ✓ Lente criada: {$lens->name}\n";

    $memory = \App\Models\Equipment::firstOrCreate(
        ['serial_number' => 'SD-TEST-001'],
        [
            'name' => 'SanDisk 64GB SD Card (Teste)',
            'category_id' => $category->id,
            'equipment_type_id' => $memoryType->id,
            'status' => 'available',
        ]
    );
    echo "   ✓ Cartão de Memória criado: {$memory->name}\n";

    $battery = \App\Models\Equipment::firstOrCreate(
        ['serial_number' => 'BAT-TEST-001'],
        [
            'name' => 'Bateria Canon LP-E6N (Teste)',
            'category_id' => $category->id,
            'equipment_type_id' => $batteryType->id,
            'status' => 'available',
        ]
    );
    echo "   ✓ Bateria criada: {$battery->name}\n";

    // Configurar acessórios recomendados para Câmara de Foto
    if ($cameraType) {
        echo "\n   Configurando acessórios para Câmara de Foto...\n";

        $cameraType->recommendedAccessories()->syncWithoutDetaching([
            $lens->id => [
                'default_quantity' => 1,
                'notes' => 'Lente recomendada para fotografia',
            ],
            $memory->id => [
                'default_quantity' => 2,
                'notes' => 'Cartão de memória para armazenamento',
            ],
            $battery->id => [
                'default_quantity' => 1,
                'notes' => 'Bateria de sobressalente',
            ],
        ]);

        echo "   ✓ Acessórios configurados com sucesso!\n";
    }
}

// 4. Testar API
echo "\n🔍 Testando API de Acessórios:\n";
$testEquipment = \App\Models\Equipment::whereNotNull('equipment_type_id')->first();

if ($testEquipment) {
    echo "   Equipamento de teste: {$testEquipment->name} (ID: {$testEquipment->id})\n";
    echo "   Tipo: {$testEquipment->equipmentType->name}\n";

    // Simular chamada da API
    $type = \App\Models\EquipmentType::with('recommendedAccessories')->find($testEquipment->equipment_type_id);

    if ($type && $type->recommendedAccessories->count() > 0) {
        echo "   ✅ API retorna " . $type->recommendedAccessories->count() . " acessórios:\n";
        foreach ($type->recommendedAccessories as $acc) {
            echo "      - {$acc->name} (qty: {$acc->pivot->default_quantity})\n";
        }

        echo "\n📝 URL para testar no browser:\n";
        echo "   /api/type-accessories/equipment/{$testEquipment->id}\n";
    } else {
        echo "   ⚠️  Este tipo de equipamento não tem acessórios recomendados configurados!\n";
    }
} else {
    echo "   ⚠️  Não há equipamentos com tipo configurado para testar!\n";
}

echo "\n=== FIM DO DEBUG ===\n";
