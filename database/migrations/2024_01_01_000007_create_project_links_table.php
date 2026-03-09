<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('url');
            $table->string('type')->default('reference'); // reference, tutorial, resource, shop, documentation
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['project_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_links');
    }
};
