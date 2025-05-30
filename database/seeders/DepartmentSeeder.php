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
            ['name' => 'Administration'],
            ['name' => 'Prefect of Student Affairs'], 
            ['name' => 'Human Resource'],
            ['name' => 'Finance & Accounting'], 
            ['name' => 'General Administrative Services'],
            ['name' => 'Basic Education'],
            ['name' => 'Higher Education'], 
            ['name' => 'Quality Assurance & Compliance Office'],
            ['name' => 'Data Privacy Office'],
            ['name' => 'Management Information Systems'],
            ['name' => 'Library'], 
            ['name' => 'Registration and Admissions'],
        ]);
    }
}
