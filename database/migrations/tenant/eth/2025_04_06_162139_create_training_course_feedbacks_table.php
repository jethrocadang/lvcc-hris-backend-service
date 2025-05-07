<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the training_course_feedbacks table.
 *
 * This table stores feedback provided by employees after training.
 *
 * Columns:
 * - course_id: Course being rated.
 * - employee_id: Who gave the feedback.
 * - rating: Numeric score.
 * - comment: Optional remarks or notes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_course_feedbacks', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->unsignedBigInteger('course_id')->index()->nullable();
            $table->unsignedBigInteger('employee_id')->index()->nullable();

            $table->integer('rating'); // Numeric score
            $table->string('comment')->nullable(); // Optional comment

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_course_feedbacks');
    }
};
