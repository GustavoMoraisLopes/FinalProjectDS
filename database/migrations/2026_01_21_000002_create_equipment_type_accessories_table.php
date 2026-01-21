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
        Schema::create('equipment_type_accessories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_type_id')->constrained('equipment_types')->onDelete('cascade');
            $table->foreignId('accessory_id')->constrained('equipments')->onDelete('cascade');
            $table->integer('default_quantity')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['equipment_type_id', 'accessory_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_type_accessories');
    }
};
