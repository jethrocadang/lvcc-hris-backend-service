<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPost;

class JobPostSeeder extends Seeder
{
    public function run(): void
    {
        JobPost::factory()->count(10)->create();
    }
}
