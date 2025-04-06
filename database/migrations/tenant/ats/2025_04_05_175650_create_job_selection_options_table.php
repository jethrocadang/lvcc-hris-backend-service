<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * Creates the job_selection_options table.
     * This links job_applications to job_postings, allowing applicants
     * to choose up to 2 job options with priority (1 or 2).
     */
    public function up(): void
    {
        Schema::create('job_selection_options', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->foreignId('job_application_id')
                ->constrained('job_applications')
                ->onDelete('cascade'); // Cascade delete if application is removed

            $table->foreignId('job_id')
                ->constrained('job_posts')
                ->onDelete('cascade'); // Cascade delete if job is removed

            $table->unsignedTinyInteger('priority'); // 1 = first choice, 2 = second choice

            $table->enum('status', ['accepted', 'rejected'])->nullable(); // Admin decision on this option

            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_selection_options');
    }
};

