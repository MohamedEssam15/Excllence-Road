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
        Schema::create('courses_status_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_status_id')->constrained('courses_statuses')->onDelete('cascade');
            $table->string('locale'); // e.g., 'en', 'ar'
            $table->string('name'); // Translated name
            $table->unique(['course_status_id', 'locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses_status_translations');
    }
};
