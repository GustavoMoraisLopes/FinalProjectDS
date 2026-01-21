<?php

namespace Database\Seeders;

use App\Models\EquipmentType;
use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentTypeAccessoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar tipos de equipamento
        $cameraType = EquipmentType::where('name', 'Câmara de Foto')->first();
        $lensType = EquipmentType::where('name', 'Lente')->first();
        $memoryCardType = EquipmentType::where('name', 'Cartão de Memória')->first();
        $batteryType = EquipmentType::where('name', 'Bateria')->first();
        $chargerType = EquipmentType::where('name', 'Carregador')->first();
        $tripodType = EquipmentType::where('name', 'Tripé')->first();
        $computerType = EquipmentType::where('name', 'Computador')->first();

        // Buscar acessórios por tipo
        $batteries = Equipment::whereHas('equipmentType', function ($q) {
            $q->where('name', 'Bateria');
        })->take(2)->get();

        $memoryCards = Equipment::whereHas('equipmentType', function ($q) {
            $q->where('name', 'Cartão de Memória');
        })->take(2)->get();

        $lenses = Equipment::whereHas('equipmentType', function ($q) {
            $q->where('name', 'Lente');
        })->take(2)->get();

        $chargers = Equipment::whereHas('equipmentType', function ($q) {
            $q->where('name', 'Carregador');
        })->take(1)->get();

        $tripods = Equipment::whereHas('equipmentType', function ($q) {
            $q->where('name', 'Tripé');
        })->take(1)->get();

        $keyboards = Equipment::whereHas('equipmentType', function ($q) {
            $q->where('name', 'Rato/Teclado');
        })->take(1)->get();

        // Câmaras de Foto recomenda: Lentes, Baterias, Cartões, Carregador, Tripé
        if ($cameraType && $lenses->isNotEmpty() && $batteries->isNotEmpty() && $memoryCards->isNotEmpty()) {
            foreach ($lenses as $lens) {
                $cameraType->recommendedAccessories()->syncWithoutDetaching([
                    $lens->id => [
                        'default_quantity' => 1,
                        'notes' => 'Lente recomendada',
                    ]
                ]);
            }

            foreach ($batteries as $battery) {
                $cameraType->recommendedAccessories()->syncWithoutDetaching([
                    $battery->id => [
                        'default_quantity' => 1,
                        'notes' => 'Bateria de sobressalente',
                    ]
                ]);
            }

            foreach ($memoryCards as $card) {
                $cameraType->recommendedAccessories()->syncWithoutDetaching([
                    $card->id => [
                        'default_quantity' => 2,
                        'notes' => 'Cartão de memória de armazenamento',
                    ]
                ]);
            }

            foreach ($chargers as $charger) {
                $cameraType->recommendedAccessories()->syncWithoutDetaching([
                    $charger->id => [
                        'default_quantity' => 1,
                        'notes' => 'Carregador de bateria',
                    ]
                ]);
            }

            foreach ($tripods as $tripod) {
                $cameraType->recommendedAccessories()->syncWithoutDetaching([
                    $tripod->id => [
                        'default_quantity' => 1,
                        'notes' => 'Tripé para estabilização',
                    ]
                ]);
            }
        }

        // Computadores recomenda: Teclado/Rato
        if ($computerType && $keyboards->isNotEmpty()) {
            foreach ($keyboards as $keyboard) {
                $computerType->recommendedAccessories()->syncWithoutDetaching([
                    $keyboard->id => [
                        'default_quantity' => 1,
                        'notes' => 'Periférico de entrada',
                    ]
                ]);
            }
        }

        $this->command->info('Acessórios recomendados por tipo de equipamento seedados com sucesso!');
    }
}
