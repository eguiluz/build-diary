<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('birthday')->nullable();
            $table->boolean('birthday_reminder')->default(false);
            $table->integer('reminder_days_before')->default(7);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'name']);
            $table->index('birthday');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
