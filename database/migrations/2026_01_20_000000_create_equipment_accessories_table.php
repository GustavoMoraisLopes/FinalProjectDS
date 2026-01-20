<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('equipment_accessories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')
                ->constrained('equipments')
                ->onDelete('cascade');
            $table->foreignId('accessory_id')
                ->constrained('equipments')
                ->onDelete('cascade');
            $table->integer('default_quantity')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Evitar duplicatas
            $table->unique(['equipment_id', 'accessory_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_accessories');
    }
};
