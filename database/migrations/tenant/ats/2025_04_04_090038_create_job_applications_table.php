<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This method creates the 'job_applications' table with the specified columns and constraints.
     */
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id(); // Primary key column
            $table->foreignId('job_applicant_id') // Foreign key referencing 'job_applicants' table
                  ->constrained('job_applicants')
                  ->onDelete('cascade');
            $table->string('portal_token'); // Column to store a unique token for the application
            $table->timestamps(); // Adds 'created_at' and 'updated_at' timestamp columns
        });
    }

    /**
     * Reverse the migrations.
     * This method drops the 'job_applications' table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
