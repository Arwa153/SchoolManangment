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
        // Create class_teacher pivot table
        if (!Schema::hasTable('class_teacher')) {
            Schema::create('class_teacher', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('class_id');
                $table->unsignedBigInteger('teacher_id');
                $table->timestamps();
                
                $table->unique(['class_id', 'teacher_id']);
                $table->index(['class_id']);
                $table->index(['teacher_id']);
            });
        }

        // Create timetables table
        if (!Schema::hasTable('timetables')) {
            Schema::create('timetables', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('teacher_id');
                $table->unsignedBigInteger('class_id');
                $table->enum('day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
                $table->time('start_time');
                $table->time('end_time');
                $table->string('subject');
                $table->text('notes')->nullable();
                $table->timestamps();
                
                $table->index(['teacher_id', 'day', 'start_time']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetables');
        Schema::dropIfExists('class_teacher');
    }
};
