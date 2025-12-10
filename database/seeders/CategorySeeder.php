<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Câmara', 'icon' => 'bi-camera', 'description' => 'Câmaras fotográficas e de vídeo'],
            ['name' => 'Acessório', 'icon' => 'bi-tools', 'description' => 'Acessórios diversos'],
            ['name' => 'Áudio', 'icon' => 'bi-headphones', 'description' => 'Equipamento de áudio'],
            ['name' => 'Computador', 'icon' => 'bi-laptop', 'description' => 'Computadores e laptops'],
            ['name' => 'Periférico', 'icon' => 'bi-mouse', 'description' => 'Periféricos de computador'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
