<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the training_course_enrollments table.
 *
 * This table records employees' enrollment in training courses.
 *
 * Columns:
 * - course_id: Refers to the course being enrolled in.
 * - employee_id: Refers to the enrolling employee.
 * - enrollment_date: Timestamp of enrollment.
 * - status: Indicates whether the enrollment is active or cancelled.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_course_enrollments', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->foreignId('course_id')->constrained('employee_training_courses')->onDelete('cascade'); // Linked course
            $table->foreignId('employee_id')->index(); // Refers to hris_db (landlord) employees table

            $table->timestamp('enrollment_date')->useCurrent(); // When enrolled

            $table->enum('status', ['active', 'cancelled'])->default('active'); // Enrollment state
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_course_enrollments');
    }
};
