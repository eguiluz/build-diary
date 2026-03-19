<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diary_entry_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diary_entry_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('disk')->default('public');
            $table->string('original_name');
            $table->string('caption')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['diary_entry_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diary_entry_images');
    }
};
