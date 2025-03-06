<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewTenantModel;

class TenantSeeder extends Seeder
{
    public function run()
    {
        NewTenantModel::create([
            'name' => 'ats',
            'database' => 'ats_db',
        ]);

        NewTenantModel::create([
            'name' => 'eth',
            'database' => 'eth_db',
        ]);
    }
}

