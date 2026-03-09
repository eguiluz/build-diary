<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diary_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->text('content');
            $table->string('type')->default('note'); // note, progress, milestone, issue, solution
            $table->date('entry_date');
            $table->time('entry_time')->nullable();
            $table->integer('time_spent_minutes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'entry_date']);
            $table->index(['project_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diary_entries');
    }
};
