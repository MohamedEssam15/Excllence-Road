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
        Schema::table('exams_users', function (Blueprint $table) {
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->decimal('degree')->nullable();
            $table->timestamp('start_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams_users', function (Blueprint $table) {
            $table->dropColumn('degree');
            $table->dropColumn('start_time');
            $table->dropConstrainedForeignId('course_id');
        });
    }
};
