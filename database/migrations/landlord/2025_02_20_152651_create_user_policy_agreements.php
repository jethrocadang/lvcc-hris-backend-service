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
        Schema::create('user_policy_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_version_id')->constrained()->onDelete('cascade');

            // Di naka foreignId, old version gamit ko para madali magets.
            // Employee reference (HRIS - landlord_db)
            $table->unsignedBigInteger('employee_id')->nullable()->unique();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            // naka index lang kasi naka store sa kabilang ats_db ID nito
            // Job Applicant reference (ATS - ats_db)
            $table->unsignedBigInteger('job_applicant_id')->nullable()->index();
            $table->foreign('job_applicant_id')->references('id')->on('job_applicants')->onDelete('cascade');

            $table->timestamp('policy_accepted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_policy_agreements');
    }
};
