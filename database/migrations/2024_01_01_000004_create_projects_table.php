<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('status_id')->constrained('project_statuses')->restrictOnDelete();
            $table->foreignId('person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->date('due_date')->nullable();
            $table->date('started_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->integer('priority')->default(0);
            $table->boolean('is_archived')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'slug']);
            $table->index(['user_id', 'status_id']);
            $table->index(['user_id', 'due_date']);
            $table->index(['user_id', 'is_archived']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
