<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Support\Facades\DB;

class DepartmentPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve department and position IDs
        $departments = Department::all();
        $positions = Position::all();

        $data = [];

        foreach ($departments as $department) {
            foreach ($positions as $position) {
                $data[] = [
                    'department_id' => $department->id,
                    'position_id' => $position->id,
                ];
            }
        }

        DB::table('department_position')->insert($data);
    }
}
