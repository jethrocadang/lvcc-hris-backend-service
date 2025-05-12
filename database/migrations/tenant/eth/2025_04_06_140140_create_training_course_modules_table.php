<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the training_course_modules table.
 *
 * This table defines the content modules within each training course.
 *
 * Columns:
 * - course_id: The training course this module belongs to.
 * - title: Title of the module.
 * - description: Description or summary of the module.
 * - type: The type of module content (video series or text module).
 * - sequence_order: Order of modules within the course.
 * - video_url: Link to video resource (if applicable).
 * - thumbnail_url: Optional module image.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_course_modules', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->unsignedBigInteger('course_id')->index()->nullable(); // Linked course

            $table->string('title'); // Name of module
            $table->longText('description'); // Overview of module

            $table->enum('type', ['video series', 'text module']); // Content type
            $table->integer('sequence_order'); // Display order

            $table->string('video_url')->nullable(); // Video resource (optional)
            $table->string('thumbnail_url')->nullable(); // Image (optional)

            $table->string('file_content')->nullable();// module file upload (Optional)
            $table->longText('text_content')->nullable();// text course (Optional)
            $table->string('image_content')->nullable();// iamge upload (Optional)


            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_course_modules');
    }
};
