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
        Schema::create('inventory_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('borrower_name');
            $table->string('borrower_contact')->nullable();
            $table->date('lent_at');
            $table->date('expected_return_at')->nullable();
            $table->date('returned_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('condition_at_loan')->nullable();
            $table->string('condition_at_return')->nullable();
            $table->timestamps();

            $table->index(['inventory_item_id', 'returned_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_loans');
    }
};
