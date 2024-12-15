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
        Schema::create('feature_contents', function (Blueprint $table) {
            $table->id();
            $table->string('subject')->nullable();
            $table->string('cover_photo')->nullable();
            $table->string('cover_video')->nullable();
            $table->string('type')->default('photo');
            $table->string('modelable_type')->nullable();
            $table->foreignId('course_id')->nullable()->constrained('courses')->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('packages')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_contents');
    }
};
