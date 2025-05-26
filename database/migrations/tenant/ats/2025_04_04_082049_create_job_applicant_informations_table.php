<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This method creates the 'job_applicant_informations' table, which stores additional details about job applicants.
     */
    public function up(): void
    {
        Schema::create('job_applicant_informations', function (Blueprint $table) {
            $table->id(); // Primary key (auto-incrementing ID)

            // Foreign key linking to the job_applicants table, ensuring each applicant has a unique record
            $table->foreignId('job_applicant_id')->unique()->constrained('job_applicants')->onDelete('cascade');

            // Personal information
            $table->string('current_address')->nullable(); // Applicant's current residential address
            $table->string('contact_number')->nullable(); // Applicant's contact number

            // Religious background (optional fields)
            $table->string('religion')->nullable(); // Religion of the applicant
            $table->string('locale_and_division')->nullable(); // Locale/church affiliation
            $table->string('servant_name')->nullable(); // Name of a church servant (e.g., mentor or spiritual guide)
            $table->string('servant_contact_number')->nullable(); // Contact number of the servant
            $table->string('date_of_baptism')->nullable(); // Date of applicant's baptism

            // Church status: active, inactive, or suspended
            $table->enum('church_status', ['active', 'inactive', 'suspended'])->default('active')->nullable();

            // Church committee involvement (optional)
            $table->string('church_commitee')->nullable();

            // Educational background
            $table->string('educational_attainment')->nullable(); // Highest education attained
            $table->string('course_or_program')->nullable(); // Course or program studied
            $table->string('school_graduated')->nullable(); // Name of the school graduated from
            $table->string('year_graduated')->nullable(); // Graduation year

            // Employment status
            $table->boolean('is_employed')->default(false); // Indicates if the applicant is currently employed

            // Documents (optional file URLs)
            $table->string('current_work')->nullable(); // URL of the uploaded resume
            $table->string('last_work')->nullable(); // URL of the uploaded resume
            $table->string('resume')->nullable(); // URL of the uploaded resume
            $table->string('transcript_of_records')->nullable(); // URL of the uploaded transcript of records

            // Relocation preference
            $table->boolean('can_relocate')->default(true); // Indicates if the applicant is open to relocation

            // Timestamps for record creation and updates
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * This method drops the 'job_applicant_informations' table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applicant_informations');
    }
};
