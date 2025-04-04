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
            $table->foreignId('applicant_id')->unique()->constrained('job_applicants')->onDelete('cascade');

            // Personal information
            $table->string('current_address'); // Applicant's current residential address
            $table->string('contact_number'); // Applicant's contact number

            // Religious background (optional fields)
            $table->string('religion')->nullable(); // Religion of the applicant
            $table->string('locale')->nullable(); // Locale/church affiliation
            $table->string('division')->nullable(); // Division within the church
            $table->string('servant_name')->nullable(); // Name of a church servant (e.g., mentor or spiritual guide)
            $table->string('servant_contact_number')->nullable(); // Contact number of the servant
            $table->date('date_of_baptism')->nullable(); // Date of applicant's baptism

            // Church status: active, inactive, or suspended
            $table->enum('church_status', ['active', 'inactive', 'suspended'])->default('active');

            // Church committee involvement (optional)
            $table->string('church_commitee')->nullable();

            // Educational background
            $table->enum('educational_attainment', ['high_school', 'bachelor', 'master', 'doctorate'])->nullable(); // Highest education attained
            $table->string('course_or_program')->nullable(); // Course or program studied
            $table->string('school_graduated')->nullable(); // Name of the school graduated from
            $table->year('year_graduated')->nullable(); // Graduation year

            // Employment status
            $table->boolean('is_employed')->default(false); // Indicates if the applicant is currently employed

            // Documents (optional file URLs)
            $table->string('resume_url')->nullable(); // URL of the uploaded resume
            $table->string('transcript_of_records_url')->nullable(); // URL of the uploaded transcript of records

            // Relocation preference
            $table->boolean('can_relocate')->default(false); // Indicates if the applicant is open to relocation

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
