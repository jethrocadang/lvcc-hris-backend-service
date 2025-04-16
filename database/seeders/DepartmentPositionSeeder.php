<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\JobPosition;
use Illuminate\Support\Facades\DB;

class DepartmentPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all();
        $positions = JobPosition::all();
    
        $data = [];
    
        foreach ($departments as $department) {
            foreach ($positions as $position) {
                $data[] = [
                    'department_id' => $department->id,
                    'job_position_id' => $position->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
    
        DB::table('department_positions')->insert($data);
    }
}
