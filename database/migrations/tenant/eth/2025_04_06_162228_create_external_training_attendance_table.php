<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the external_training_attendance table.
 *
 * This table logs training completed by employees outside the system.
 *
 * Columns:
 * - employee_id: Who attended the external training.
 * - training_type: Type/category of training.
 * - certificate_url: Link to proof of completion.
 * - date_started: Start date of training.
 * - date_completed: Completion date of training.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_training_attendance', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->foreignId('employee_id')->index(); // Who attended
            // TODO ADD TABLE FOR TRAINING TYPE
            $table->enum('training_type', ['onboarding', 'compliance', 'external', 'other']); // Add relevant types
            $table->string('certificate_url')->nullable(); // Proof/certificate

            $table->date('date_started'); // Start of training
            $table->date('date_completed'); // End of training

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_training_attendance');
    }
};
