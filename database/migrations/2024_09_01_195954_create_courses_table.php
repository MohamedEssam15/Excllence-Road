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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cover_photo_name')->nullable();
            $table->longText('description')->nullable();
            $table->string('teacher_commision')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->string('currency')->nullable();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('level_id')->nullable()->constrained('course_levels')->nullOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_populer')->default(false);
            $table->boolean('is_specific')->default(false); //  if the content to specific Study stage
            $table->string('specific_to')->nullable(); // if the content to specific Study stage
            $table->foreignId('status_id')->nullable()->constrained('courses_statuses')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
