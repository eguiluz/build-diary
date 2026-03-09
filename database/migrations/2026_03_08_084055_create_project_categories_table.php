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
        Schema::create('project_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('color')->default('gray');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Add category_id column to projects table
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('status_id')->constrained('project_categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('project_categories');
    }
};
