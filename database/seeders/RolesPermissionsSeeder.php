<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $systemAdminRole = Role::create(['name' => 'System Admin']);
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $hrOfficerRole = Role::create(['name' => 'HR Officer']);
        $hrRecruiterRole = Role::create(['name' => 'HR Recruiter']);
        $hrStaffRole = Role::create(['name' => 'HR Staff']);
        $departmentHeadRole = Role::create(['name' => 'Department Head']);

        // Default Role
        $employeeRole = Role::create(['name' => 'Employee']);

        $viewCourse = Permission::create(['permission' => 'view:course']);
        $updateCourse = Permission::create(['permission' => 'update:course']);
        $createCourse = Permission::create(['permission' => 'create:course']);
        $deleteCourse = Permission::create(['permission' => 'delete:course']);
    }
}
