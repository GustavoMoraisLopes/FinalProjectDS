<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        \App\Models\User::create([
            'name' => 'Admin ISTEC',
            'email' => 'admin@my.istec.pt',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
            'institution' => 'istec',
        ]);

        // User ISTEC
        \App\Models\User::create([
            'name' => 'Gustavo Morais',
            'email' => 'gustavo@my.istec.pt',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
            'institution' => 'istec',
        ]);

        // User IPTA
        \App\Models\User::create([
            'name' => 'João Silva',
            'email' => 'joao@ipta.pt',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
            'institution' => 'ipta',
        ]);
    }
}
