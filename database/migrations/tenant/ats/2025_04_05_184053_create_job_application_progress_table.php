<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * Creates the job_application_progress table.
     * Tracks the progress of each application through hiring phases.
     */
    public function up(): void
    {
        Schema::create('job_application_progress', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->foreignId('application_id')
                ->constrained('job_applications')
                ->onDelete('cascade'); // Remove progress if application is deleted

            $table->foreignId('job_application_phase_id')
                ->constrained('job_application_phases')
                ->onDelete('cascade'); // Remove progress if phase is deleted

            $table->unsignedBigInteger('reviewed_by')->index(); // Employee ID (from landlord database)

            $table->enum('status', ['accepted', 'rejected'])->nullable(); // Phase result

            $table->timestamp('start_date')->nullable(); // When the phase started
            $table->timestamp('end_date')->nullable();   // When the phase ended

            $table->timestamps(); // created_at and updated_at

            // Note: No FK constraint on reviewed_by since it's a cross-database reference
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_application_progress');
    }
};
