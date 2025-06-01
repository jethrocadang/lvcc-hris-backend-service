<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $systemAdminRole = Role::create(['name' => 'System Admin']);
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $adminRole = Role::create(['name' => 'Admin']);
        $departmentHeadRole = Role::create(['name' => 'Department Head']);
        $employeeRole = Role::create(['name' => 'Employee']);
        $jobApplicantRole = Role::create(['name' => 'Job Applicant']);

        // Default Role
        $employeeRole = Role::create(['name' => 'Employee']);
    }
}
