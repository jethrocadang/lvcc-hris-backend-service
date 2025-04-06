<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the `job_posts` table which stores job openings posted by the organization.
     * Each record represents a public-facing job posting that applicants can apply to.
     */
    public function up(): void
    {
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id(); // Primary key (auto-incrementing)

            $table->enum('work_type', ['full-time', 'part-time', 'internship']);
            // Specifies the employment arrangement (e.g., full-time or internship)

            $table->enum('job_type', ['onsite', 'remote', 'hybrid']);
            // Describes the work setup (e.g., working onsite, remotely, or hybrid)

            $table->string('title');
            // Title of the job position (e.g., Math Teacher, Web Developer)

            $table->text('description');
            // Full description of job responsibilities, qualifications, etc.

            $table->string('icon_url')->nullable();
            // Optional image/icon to visually represent the job in the UI

            $table->enum('status', ['open', 'closed'])->default('open');
            // Job availability; 'open' means the job is accepting applications

            $table->string('location')->default('Apalit');
            // Location of the job posting (default is Apalit)

            $table->string('schedule')->nullable();
            // Optional description of working hours (e.g., 8AM–5PM M-F)

            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the `job_posts` table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
