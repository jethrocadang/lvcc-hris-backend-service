<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration creates the `interview_schedule_slots` table that stores
     * information about interview schedule slots. Each slot is linked to an admin
     * (via a foreign key) and has a date, start time, and status.
     *
     * This is for the feature for the interview scheduling of job applicats.
     *
     * Columns:
     * - admin: The user (admin) responsible for the interview slot.
     * - scheduled_date: The date of the interview.
     * - start_time: The start time of the interview.
     * - slot_status: The status of the slot (available, booked, completed).
     */
    public function up()
    {
        Schema::create('interview_schedule_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin')  // 'admin' references the 'id' column in the 'users' table
                  ->constrained('users')
                  ->onDelete('cascade')->nullable();
            $table->date('scheduled_date'); // The date of the interview
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * This will drop the `interview_schedule_slots` table.
     */
    public function down()
    {
        Schema::dropIfExists('interview_schedule_slots'); // Rollback method to drop the table
    }
};
