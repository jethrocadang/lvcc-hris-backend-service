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
        Schema::create('interview_schedule_time_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_slot_id')
                ->constrained('interview_schedule_slots')
                ->onDelete('cascade');
            $table->time('start_time');
            $table->boolean('is_available')->default(true);
            $table->boolean('is_booked')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_schedule_time_slots');
    }
};
