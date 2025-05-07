<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the employee_course_progress table.
 *
 * This table tracks an employee’s progress through training modules.
 * It supports both text and video module types.
 *
 * Columns:
 * - employee_id: References the employee assigned to the course.
 * - course_id: References the overall training course.
 * - module_id: References a specific module (within the course).
 * - status: Tracks the employee’s progress status.
 * - watched_seconds: (Optional) For video modules, how many seconds have been watched.
 * - last_position: (Optional) Last watched position (used for resume).
 * - completion_date: Timestamp when the module was completed.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_course_progress', function (Blueprint $table) {
            $table->id();

            // Employee assigned to this module
            $table->unsignedBigInteger('employee_id')->index()->nullable(); // Linked employee
            $table->unsignedBigInteger('course_id')->index()->nullable(); // Linked course
            $table->unsignedBigInteger('module_id')->index()->nullable(); // Linked module

            // Training status for this module
            $table->enum('status', ['not-started', 'in-progress', 'completed'])->default('not-started');

            // For video modules: how many seconds have been watched
            $table->integer('watched_seconds')->default(0)->nullable();

            // For video modules: where the user left off
            $table->integer('last_position')->default(0)->nullable();

            // When the employee completed this module
            $table->timestamp('completion_date')->nullable();

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_course_progress');
    }
};
