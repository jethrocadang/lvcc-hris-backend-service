<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Set tenant database to eth_db
        Config::set('database.connections.tenant.database', 'eth_db');
        DB::purge('tenant'); // Clear existing tenant connection
        DB::reconnect('tenant');

        DB::connection('tenant')->table('training_course_modules')->insert([
            [
                'course_id' => 1,
                'title' => 'Introduction to the System',
                'description' => 'Overview of the HRIS and how it benefits your organization.',
                'type' => 'video series',
                'sequence_order' => 1,
                'certificate_url' => null,
                'video_url' => 'https://www.youtube.com/watch?v=3Kxf2dHlDpQ',
                'thumbnail_url' => 'https://img.youtube.com/vi/3Kxf2dHlDpQ/0.jpg',
                'file_content' => null,
                'text_content' => null,
                'image_content' => null,
                'expiration_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
