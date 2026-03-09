<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // deadline, birthday, custom, reminder
            $table->date('event_date');
            $table->time('event_time')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_all_day')->default(true);
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_rule')->nullable();
            $table->string('color', 7)->nullable();
            $table->boolean('reminder_enabled')->default(false);
            $table->integer('reminder_minutes_before')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'event_date']);
            $table->index(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
