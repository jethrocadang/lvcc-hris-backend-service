<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the employee_training_courses table.
 *
 * This table stores the details of training programs offered to employees.
 *
 * Columns:
 * - title: Name of the course.
 * - description: Course overview or content summary.
 * - type: Categorizes course into onboarding, general, or specialized.
 * - thumbnail_url: Optional image associated with the course.
 * - max_participants: Optional limit on enrollees (nullable).
 * - current_participants: Tracks current enrollment count.
 * - enrollment_deadline: Last date employees can enroll.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_training_courses', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->unsignedBigInteger('author_id')->index()->nullable(); //refers to users table (hris)

            $table->string('title'); // Name of the course
            $table->text('description'); // Course summary or objectives
            // TODO CREATE A TRAINING CATEGORY TABLE
            $table->enum('type', ['onboarding', 'general', 'specialized']); // Type/category
            $table->string('thumbnail_url')->nullable(); // Optional course image

            $table->integer('max_participants')->nullable(); // Cap on participants
            $table->integer('current_participants')->default(0); // Tracks how many enrolled
            $table->dateTime('enrollment_deadline')->nullable(); // Last day to enroll

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_training_courses');
    }
};
