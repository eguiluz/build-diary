<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('original_name');
            $table->string('path');
            $table->string('disk')->default('local');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->string('type')->default('attachment'); // image, stl, pdf, attachment
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['project_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_files');
    }
};
