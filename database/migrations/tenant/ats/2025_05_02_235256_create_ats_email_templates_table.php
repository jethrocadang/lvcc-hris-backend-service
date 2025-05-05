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
        Schema::create('ats_email_templates', function (Blueprint $table) {
            $table->id();

            // References the user who created or last updated the template
            $table->unsignedBigInteger('user_id')->nullable()->index();

            // Indicates if this template is used for acceptance or rejection emails
            $table->enum('type', ['acceptance', 'rejection']);

            // Email subject and body content
            $table->string('subject');
            $table->text('body');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ats_email_templates');
    }
};
