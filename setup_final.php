<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 CONFIGURAÇÃO FINAL - TIPOS E ACESSÓRIOS\n\n";

// 1. Remover "(Teste)" dos nomes
echo "📝 Removendo '(Teste)' dos equipamentos...\n";
$testEquipments = \App\Models\Equipment::where('name', 'like', '%(Teste)%')->get();
foreach ($testEquipments as $eq) {
    $newName = str_replace(' (Teste)', '', $eq->name);
    $eq->update(['name' => $newName]);
    echo "   ✓ {$newName}\n";
}

// 2. Buscar tipos de equipamento
echo "\n📦 Buscando tipos de equipamento...\n";
$types = [
    'camera' => \App\Models\EquipmentType::where('name', 'Câmara de Foto')->first(),
    'video' => \App\Models\EquipmentType::where('name', 'Câmara de Vídeo')->first(),
    'lens' => \App\Models\EquipmentType::where('name', 'Lente')->first(),
    'memory' => \App\Models\EquipmentType::where('name', 'Cartão de Memória')->first(),
    'battery' => \App\Models\EquipmentType::where('name', 'Bateria')->first(),
    'charger' => \App\Models\EquipmentType::where('name', 'Carregador')->first(),
    'tripod' => \App\Models\EquipmentType::where('name', 'Tripé')->first(),
    'mic' => \App\Models\EquipmentType::where('name', 'Microfone')->first(),
];

// 3. Atribuir tipos aos equipamentos existentes baseado no nome
echo "\n🏷️  Atribuindo tipos aos equipamentos...\n";

$equipments = \App\Models\Equipment::all();
foreach ($equipments as $eq) {
    $name = strtolower($eq->name);

    // Lentes (primeiro, antes de câmaras)
    if (preg_match('/\blente\b|lens|mm f\/|ef |rf |50mm|24-70|70-200/i', $name)) {
        $eq->equipment_type_id = $types['lens']->id;
        echo "   ✓ {$eq->name} → Lente\n";
    }

    // Baterias (antes de câmaras)
    elseif (preg_match('/\bbateria\b|battery|lp-e6|lp-e17|power pack/i', $name)) {
        $eq->equipment_type_id = $types['battery']->id;
        echo "   ✓ {$eq->name} → Bateria\n";
    }

    // Cartões de memória
    elseif (preg_match('/\bsd\b|card|cartão|sandisk|memory|compact.*flash|xqd/i', $name)) {
        $eq->equipment_type_id = $types['memory']->id;
        echo "   ✓ {$eq->name} → Cartão de Memória\n";
    }

    // Carregadores
    elseif (preg_match('/carregador|charger/i', $name)) {
        $eq->equipment_type_id = $types['charger']->id;
        echo "   ✓ {$eq->name} → Carregador\n";
    }

    // Câmaras de Vídeo
    elseif (preg_match('/video|vídeo|camcorder/i', $name) &&
            preg_match('/canon|sony|nikon|camera|câmara|eos|alpha/i', $name)) {
        $eq->equipment_type_id = $types['video']->id;
        echo "   ✓ {$eq->name} → Câmara de Vídeo\n";
    }

    // Câmaras de Foto
    elseif (preg_match('/canon|sony|nikon|fuji|olympus|panasonic/i', $name) &&
            preg_match('/eos|alpha|z\d|d\d{3,4}|r5|r6|a7|gh\d/i', $name)) {
        $eq->equipment_type_id = $types['camera']->id;
        echo "   ✓ {$eq->name} → Câmara de Foto\n";
    }

    // Tripés
    elseif (preg_match('/tripod|tripé|manfrotto|gitzo|benro/i', $name)) {
        $eq->equipment_type_id = $types['tripod']->id;
        echo "   ✓ {$eq->name} → Tripé\n";
    }

    // Microfones
    elseif (preg_match('/\bmic\b|rode|audio|microfone|microphone/i', $name)) {
        $eq->equipment_type_id = $types['mic']->id;
        echo "   ✓ {$eq->name} → Microfone\n";
    }

    $eq->save();
}

// 4. Configurar acessórios recomendados para Câmaras
echo "\n🔗 Configurando acessórios recomendados...\n";

$cameras = \App\Models\Equipment::where('equipment_type_id', $types['camera']->id)->get();
$lenses = \App\Models\Equipment::where('equipment_type_id', $types['lens']->id)->get();
$memoryCards = \App\Models\Equipment::where('equipment_type_id', $types['memory']->id)->get();
$batteries = \App\Models\Equipment::where('equipment_type_id', $types['battery']->id)->get();
$chargers = \App\Models\Equipment::where('equipment_type_id', $types['charger']->id)->get();
$tripods = \App\Models\Equipment::where('equipment_type_id', $types['tripod']->id)->get();

echo "\n   📷 Câmaras de Foto ({$cameras->count()})\n";
echo "   🔍 Lentes ({$lenses->count()})\n";
echo "   💾 Cartões ({$memoryCards->count()})\n";
echo "   🔋 Baterias ({$batteries->count()})\n";
echo "   ⚡ Carregadores ({$chargers->count()})\n";
echo "   🎬 Tripés ({$tripods->count()})\n";

// Configurar acessórios para tipo "Câmara de Foto"
if ($types['camera']) {
    // Limpar configurações anteriores
    $types['camera']->recommendedAccessories()->detach();

    // Adicionar lentes
    foreach ($lenses as $lens) {
        $types['camera']->recommendedAccessories()->attach($lens->id, [
            'default_quantity' => 1,
            'notes' => 'Lente recomendada',
        ]);
    }

    // Adicionar cartões
    foreach ($memoryCards as $card) {
        $types['camera']->recommendedAccessories()->attach($card->id, [
            'default_quantity' => 2,
            'notes' => 'Cartão de memória',
        ]);
    }

    // Adicionar baterias
    foreach ($batteries as $battery) {
        $types['camera']->recommendedAccessories()->attach($battery->id, [
            'default_quantity' => 1,
            'notes' => 'Bateria extra',
        ]);
    }

    // Adicionar carregadores
    foreach ($chargers as $charger) {
        $types['camera']->recommendedAccessories()->attach($charger->id, [
            'default_quantity' => 1,
            'notes' => 'Carregador de bateria',
        ]);
    }

    // Adicionar tripés
    foreach ($tripods as $tripod) {
        $types['camera']->recommendedAccessories()->attach($tripod->id, [
            'default_quantity' => 1,
            'notes' => 'Tripé para estabilização',
        ]);
    }

    $totalAccessories = $lenses->count() + $memoryCards->count() + $batteries->count() + $chargers->count() + $tripods->count();
    echo "\n   ✅ Configurados {$totalAccessories} acessórios para Câmaras de Foto\n";
}

echo "\n✅ CONFIGURAÇÃO COMPLETA!\n";
echo "\n📊 Resumo:\n";
echo "   • Equipamentos com tipo: " . \App\Models\Equipment::whereNotNull('equipment_type_id')->count() . "\n";
echo "   • Acessórios configurados: " . \DB::table('equipment_type_accessories')->count() . "\n";
