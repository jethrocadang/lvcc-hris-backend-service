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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->enum('work_type', ['full-time','part-time', 'internship']);
            $table->enum('job_type', ['onsite', 'remote', 'hybrid']);
            $table->string('title');
            $table->text('description');
            $table->string('icon_url')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->string('location')->default('Apalit');
            $table->string('schedule')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
