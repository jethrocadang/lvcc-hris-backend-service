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
        Role::create([
            'name' => 'System Admin',
            'name' => 'Super Admin',
            'name' => 'HR Officer',
            'name' => 'HR Recruiter',
            'name' => 'HR Staff',
            'name' => 'Department Head',
            'name' => 'Employee'
        ]);

        /***********************
         * ETH 
         ********/

        // Course permissions
        Permission::create([
            'name' => 'view:course',
            'name' => 'update:course',
            'name' => 'create:course',
            'name' => 'delete:course',
            'name' => 'enroll:course'
        ]);

        //Training request permissions
        Permission::create([
            //Employee-end
            'permission' => 'view:request',
            'permission' => 'create:request',
            'permission' => 'update:request',

            //Supervisor and Officer end
            'permission' => 'approve:request',
            'permission' => 'reject:request'
        ]);

        //Training permissions
        Permission::create([
            'permission' => 'create:training',
            'permission' => 'update:training',
            'permission' => 'view:training',
            'permission' => 'delete:training'
        ]);

        //External attendance permissions
        Permission::create([
            'permission' => 'view:attendance',
            'permission' => 'create:attendance',
            'permission' => 'update:attendance',
            'permission' => 'delete:attendance'
        ]);

        /***********************
         * ATS 
         ********/

         //Job posting permissions
        Permission::create([
            'permission' => 'view:jobpost',
            'permission' => 'create:jobpost',
            'permission' => 'update:jobpost',
            'permission' => 'delete:jobpost',
        ]);

        //Application permission
        Permission::create([
            'permission' => 'accept:application',
            'permission' => 'reject:application'
        ]);

        //Interview scheduling permission
        Permission::create([
            'permission' => 'view:schedule',
            'permission' => 'create:schedule',
            'permission' => 'update:schedule',
            'permission' => 'delete:schedule',
        ]);

    }
}