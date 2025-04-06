<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * Creates the job_application_phases table.
     * Defines the hiring stages (e.g., screening, interview, final review),
     * with optional email templates and ordered sequence.
     */
    public function up(): void
    {
        Schema::create('job_application_phases', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->unsignedBigInteger('email_template_id'); // Link to email_templates (not FK for flexibility)

            $table->string('name'); // Phase name (e.g., "Initial Screening")
            $table->text('description')->nullable(); // Phase description
            $table->unsignedInteger('sequence_order'); // Order of phase in the application flow

            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_application_phases');
    }
};

