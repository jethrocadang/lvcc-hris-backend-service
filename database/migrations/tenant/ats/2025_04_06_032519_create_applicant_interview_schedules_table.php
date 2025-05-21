<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This table stores the selected interview schedule by the job applicant.
     * Each schedule references a time slot from the landlord's `date_sched_slots` table
     * and links it to the applicant's progress in a specific phase of the hiring process.
     */
    public function up(): void
    {
        Schema::create('applicant_interview_schedules', function (Blueprint $table) {
            $table->id(); // Primary key (auto-incrementing)

            $table->unsignedBigInteger('interview_slot_id')->index();
            $table->unsignedBigInteger('interview_time_slot_id')->index();
            // Slot ID from landlord database (date_sched_slots)
            // No foreign key constraint due to cross-database limitations

            $table->foreignId('job_application_id')
                ->constrained('job_applications')
                ->onDelete('cascade'); // Cascade if application progress is removed

            $table->foreignId('job_application_phase_id')
                ->constrained('job_application_phases')
                ->onDelete('cascade'); // Cascade if application progress is removed

            $table->date('selected_date'); // Date selected by the applicant
            $table->time('selected_time'); // Time selected by the applicant

            $table->enum('schedule_status', ['booked', 'completed', 'cancelled'])
                ->default('booked'); // Current state of the schedule

            $table->string('location')->nullable();
            $table->string('what_to_bring')->nullable();
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the applicant_interview_schedule table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_interview_schedules');
    }
};
