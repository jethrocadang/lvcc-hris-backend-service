<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::insert([
            ['department_name' => 'HR', 'description' => 'Manages employee inside the company'],
            ['department_name' => 'IT', 'description' => 'Manages the company website'],
            ['department_name' => 'Marketing', 'description' => 'Promotes the company products'],
        ]);
    }
}
