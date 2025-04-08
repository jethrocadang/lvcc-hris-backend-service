<?php
// database/seeders/UsersTableSeeder.php

// database/seeders/UsersTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Step 1: Create all roles
        $roles = [
            'system admin',
            'super admin',
            'admin',
            'employee',
            'job applicant',
            'user',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Step 2: Create admin users and assign 'admin' role
        $admins = [
            ['name' => 'system_admin', 'email' => 'systemadmin@example.com'],
            ['name' => 'super_admin', 'email' => 'superadmin@example.com'],
            ['name' => 'admin', 'email' => 'admin@example.com'],
            ['name' => 'employee', 'email' => 'employe@example.com'],
            ['name' => 'job_applicant', 'email' => 'jobapplicant@example.com'],
            ['name' => 'user', 'email' => 'user@example.com'],
        ];

        $adminRole = Role::where('name', 'admin')->first();

        foreach ($admins as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => Hash::make('password')]
            );

            $user->assignRole($adminRole);
        }
    }
}

