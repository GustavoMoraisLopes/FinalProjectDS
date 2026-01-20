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
        Schema::create('reservation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')
                ->constrained('reservations')
                ->onDelete('cascade');
            $table->foreignId('equipment_id')
                ->constrained('equipments')
                ->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->enum('item_type', ['main', 'accessory'])->default('main');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_items');
    }
};
