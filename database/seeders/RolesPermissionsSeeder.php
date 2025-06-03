<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\ModelHasRoles;
use Spatie\Permission\Models\ModelHasPermissions;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate pivot tables first
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();

        // Truncate main tables
        Role::truncate();
        Permission::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create roles
        $roles = [
            'System Admin',
            'Super Admin',
            'HR Officer',
            'HR Recruiter',
            'HR Staff',
            'Department Head',
            'Employee'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Course permissions
        $coursePermissions = [
            'view:course',
            'update:course',
            'create:course',
            'delete:course',
            'enroll:course'
        ];

        foreach ($coursePermissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        // Training request permissions
        $trainingRequestPermissions = [
            'view:request',
            'create:request',
            'update:request',
            'approve:request',
            'reject:request'
        ];

        foreach ($trainingRequestPermissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        // Training permissions
        $trainingPermissions = [
            'create:training',
            'update:training',
            'view:training',
            'delete:training'
        ];

        foreach ($trainingPermissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        // External attendance permissions
        $attendancePermissions = [
            'view:attendance',
            'create:attendance',
            'update:attendance',
            'delete:attendance'
        ];

        foreach ($attendancePermissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        // ATS: Job posting permissions
        $jobPostPermissions = [
            'view:jobpost',
            'create:jobpost',
            'update:jobpost',
            'delete:jobpost',
        ];

        foreach ($jobPostPermissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        // Application permissions
        $appPermissions = [
            'accept:application',
            'reject:application'
        ];

        foreach ($appPermissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        // Interview scheduling permissions
        $schedulePermissions = [
            'view:schedule',
            'create:schedule',
            'update:schedule',
            'delete:schedule'
        ];

        foreach ($schedulePermissions as $perm) {
            Permission::create(['name' => $perm]);
        }
    }
}
