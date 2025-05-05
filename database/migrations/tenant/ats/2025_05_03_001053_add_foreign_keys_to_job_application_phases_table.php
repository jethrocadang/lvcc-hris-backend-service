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
        Schema::table('job_application_phases', function (Blueprint $table) {
            // Add email template references (nullable to avoid constraint issues)
            $table->unsignedBigInteger('acceptance_email_template_id')->nullable()->unique()->after('id');
            $table->unsignedBigInteger('rejection_email_template_id')->nullable()->unique()->after('acceptance_email_template_id');

            // Add foreign key constraints
            $table->foreign('acceptance_email_template_id')
                ->references('id')
                ->on('ats_email_templates')
                ->nullOnDelete();

            $table->foreign('rejection_email_template_id')
                ->references('id')
                ->on('ats_email_templates')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_application_phases', function (Blueprint $table) {
            $table->dropForeign(['acceptance_email_template_id']);
            $table->dropForeign(['rejection_email_template_id']);
            $table->dropColumn(['acceptance_email_template_id', 'rejection_email_template_id']);
        });
    }
};
