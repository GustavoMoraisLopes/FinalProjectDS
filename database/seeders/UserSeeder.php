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
            'name' => 'Ana Silva',
            'email' => 'ana@lab.pt',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
            'phone' => '912345678',
            'department' => 'TI',
        ]);

        // User
        \App\Models\User::create([
            'name' => 'João Santos',
            'email' => 'joao@lab.pt',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
            'phone' => '918765432',
            'department' => 'Desenvolvimento',
        ]);
    }
}
