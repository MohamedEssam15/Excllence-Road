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
        Schema::create('course_level_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_level_id')->constrained('course_levels')->onDelete('cascade');
            $table->string('locale'); // e.g., 'en', 'ar'
            $table->string('name'); // Translated name
            $table->unique(['course_level_id', 'locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_level_translations');
    }
};
