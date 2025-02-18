<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::insert([
            ['position_title' => 'Manager', 'description' => 'Manages employee inside MIS department'],
            ['position_title' => 'Developer', 'description' => 'Develops software for the company'],
            ['position_title' => 'Designer', 'description' => 'Designs the company website'],
        ]);
    }
}
