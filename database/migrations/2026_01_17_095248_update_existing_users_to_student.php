<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Atualizar todos os utilizadores existentes para 'student'
        DB::table('users')->update(['user_type' => 'student']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Opcional: reverter para 'teacher' (não vamos fazer rollback real)
    }
};
