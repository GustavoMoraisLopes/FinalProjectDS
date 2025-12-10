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
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('serial_number')->unique();
            $table->string('location')->nullable();
            $table->enum('status', ['available', 'maintenance', 'loaned', 'unavailable'])->default('available');
            $table->string('condition')->nullable(); 
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
