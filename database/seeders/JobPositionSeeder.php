<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobPosition;

class JobPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobPosition::insert([
            ['title' => 'Manager', 'description' => 'Manages employee inside MIS department'],
            ['title' => 'Developer', 'description' => 'Develops software for the company'],
            ['title' => 'Designer', 'description' => 'Designs the company website'],
        ]);
    }
}
