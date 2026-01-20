<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;
use App\Models\EquipmentAccessory;
use App\Models\Category;

class AccessoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Câmaras digitais
        $camera = Equipment::where('name', 'like', '%Câmara%')->first();
        if ($camera) {
            // Criar acessórios de câmara
            $battery = Equipment::create([
                'name' => 'Bateria Canon NB-13L',
                'category_id' => Category::where('name', 'Acessórios')->first()?->id ?? 1,
                'serial_number' => 'BAT-NB13L-001',
                'location' => 'Armário C',
                'owner_institution' => 'istec',
                'status' => 'available',
                'condition' => 'Bom',
                'description' => 'Bateria recarregável para câmaras Canon',
            ]);

            $card = Equipment::create([
                'name' => 'Cartão SD 128GB',
                'category_id' => Category::where('name', 'Acessórios')->first()?->id ?? 1,
                'serial_number' => 'SD-128GB-001',
                'location' => 'Armário C',
                'owner_institution' => 'istec',
                'status' => 'available',
                'condition' => 'Bom',
                'description' => 'Cartão de memória SD de alta velocidade',
            ]);

            $charger = Equipment::create([
                'name' => 'Carregador Canon CB-2LV',
                'category_id' => Category::where('name', 'Acessórios')->first()?->id ?? 1,
                'serial_number' => 'CHG-CB2LV-001',
                'location' => 'Armário C',
                'owner_institution' => 'istec',
                'status' => 'available',
                'condition' => 'Bom',
                'description' => 'Carregador de bateria para câmaras Canon',
            ]);

            // Associar como acessórios
            EquipmentAccessory::create([
                'equipment_id' => $camera->id,
                'accessory_id' => $battery->id,
                'default_quantity' => 1,
                'notes' => 'Bateria essencial para o funcionamento',
            ]);

            EquipmentAccessory::create([
                'equipment_id' => $camera->id,
                'accessory_id' => $card->id,
                'default_quantity' => 2,
                'notes' => 'Mínimo 2 cartões recomendado',
            ]);

            EquipmentAccessory::create([
                'equipment_id' => $camera->id,
                'accessory_id' => $charger->id,
                'default_quantity' => 1,
                'notes' => 'Carregador obrigatório',
            ]);

            $this->command->info('✅ Acessórios de câmara criados com sucesso!');
        }

        // Laptops
        $laptop = Equipment::where('name', 'like', '%Laptop%')->orWhere('name', 'like', '%MacBook%')->first();
        if ($laptop) {
            $charger_laptop = Equipment::create([
                'name' => 'Carregador USB-C 65W',
                'category_id' => Category::where('name', 'Acessórios')->first()?->id ?? 1,
                'serial_number' => 'CHG-USBC-65W-001',
                'location' => 'Armário A',
                'owner_institution' => 'istec',
                'status' => 'available',
                'condition' => 'Bom',
                'description' => 'Carregador USB-C universal',
            ]);

            $mouse = Equipment::create([
                'name' => 'Rato Wireless Logitech MX Master 3',
                'category_id' => Category::where('name', 'Periféricos')->first()?->id ?? 1,
                'serial_number' => 'MOUSE-MXM3-001',
                'location' => 'Armário A',
                'owner_institution' => 'istec',
                'status' => 'available',
                'condition' => 'Bom',
                'description' => 'Rato de precisão sem fio',
            ]);

            EquipmentAccessory::create([
                'equipment_id' => $laptop->id,
                'accessory_id' => $charger_laptop->id,
                'default_quantity' => 1,
                'notes' => 'Carregador para portátil',
            ]);

            EquipmentAccessory::create([
                'equipment_id' => $laptop->id,
                'accessory_id' => $mouse->id,
                'default_quantity' => 1,
                'notes' => 'Rato para melhor ergonomia',
            ]);

            $this->command->info('✅ Acessórios de laptop criados com sucesso!');
        }

        $this->command->info('✨ Seed de acessórios completado!');
    }
}
