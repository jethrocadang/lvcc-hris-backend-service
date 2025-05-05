<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the employee_training_requests table.
 *
 * This table stores internal training requests submitted by employees,
 * and tracks their approval status from a supervisor and a training officer.
 *
 * Columns:
 * - employee_id: References the requesting employee (hris_db).
 * - supervisor_id: References the supervisor reviewing the request (hris_db).
 * - officer_id: References the officer reviewing the request (hris_db).
 * - subject: Short summary of the request.
 * - body: Detailed justification or message.
 * - supervisor_status/officer_status/request_status: Workflow tracking.
 * - *_reviewed_at: Timestamps for when actions were taken.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_training_requests', function (Blueprint $table) {
            $table->id(); // Primary key

            // Foreign keys
            $table->unsignedBigInteger('employee_id')->index()->nullable(); // Refers to employees table (hris_db)
            $table->unsignedBigInteger('supervisor_id')->index()->nullable(); // Refers to users table (hris_db)
            $table->unsignedBigInteger('officer_id')->index()->nullable(); // Refers to users table (hris_db)

            // Request content
            $table->string('subject'); // Brief request title
            $table->text('body'); // Full message or details

            // Supervisor review
            $table->enum('supervisor_status', ['approved', 'pending', 'rejected'])->default('pending'); // Default: pending
            $table->timestamp('supervisor_reviewed_at')->nullable(); // When supervisor acted

            // Officer review
            $table->enum('officer_status', ['approved', 'pending', 'rejected'])->default('pending'); // Default: pending
            $table->timestamp('officer_reviewed_at')->nullable(); // When officer acted

            // Final request status (may duplicate logic but helps filtering)
            $table->enum('request_status', ['approved', 'pending', 'rejected'])->default('pending');

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_training_requests');
    }
};
