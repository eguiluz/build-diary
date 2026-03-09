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
        Schema::create('project_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->default('material');
            $table->decimal('quantity', 10, 2)->default(1);
            $table->string('unit')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2)->storedAs('quantity * unit_price');
            $table->string('supplier')->nullable();
            $table->string('url')->nullable();
            $table->date('purchased_at')->nullable();
            $table->boolean('is_purchased')->default(false);
            $table->timestamps();

            $table->index(['project_id', 'category']);
            $table->index(['project_id', 'is_purchased']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_expenses');
    }
};
