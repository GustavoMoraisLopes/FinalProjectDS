<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipments = [
            [
                'name' => 'Canon EOS R5',
                'category_id' => 1, // Câmara
                'serial_number' => 'CN123456',
                'location' => 'Armário A1',
                'status' => 'available',
                'condition' => 'Excelente',
                'description' => 'Câmara profissional mirrorless',
                'purchase_date' => '2024-01-15',
                'purchase_price' => 3500.00,
            ],
            [
                'name' => 'Sony A7S III',
                'category_id' => 1,
                'serial_number' => 'SN987654',
                'location' => 'Armário A2',
                'status' => 'available',
                'condition' => 'Bom',
                'description' => 'Câmara para vídeo profissional',
                'purchase_date' => '2024-03-20',
                'purchase_price' => 3800.00,
            ],
            [
                'name' => 'Manfrotto Tripod',
                'category_id' => 2, // Acessório
                'serial_number' => 'MN556789',
                'location' => 'Estante B1',
                'status' => 'available',
                'condition' => 'Usado',
                'description' => 'Tripé profissional',
                'purchase_date' => '2023-11-10',
                'purchase_price' => 250.00,
            ],
            [
                'name' => 'Rode VideoMic Pro',
                'category_id' => 3, // Áudio
                'serial_number' => 'RD112233',
                'location' => 'Bancada Técnica',
                'status' => 'maintenance',
                'condition' => 'Avariado',
                'description' => 'Microfone shotgun',
                'purchase_date' => '2023-05-08',
                'purchase_price' => 200.00,
            ],
        ];

        foreach ($equipments as $equipment) {
            \App\Models\Equipment::create($equipment);
        }
    }
}
