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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('category'); // tool, material, consumable, equipment, safety, other
            $table->decimal('quantity', 10, 2)->default(1);
            $table->string('unit')->nullable(); // uds, m, kg, l, etc.
            $table->decimal('min_quantity', 10, 2)->nullable(); // alert threshold
            $table->string('location')->nullable(); // workshop location
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->string('condition')->default('good'); // new, good, fair, poor, broken
            $table->string('image')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_lent')->default(false);
            $table->string('lent_to')->nullable();
            $table->date('lent_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
