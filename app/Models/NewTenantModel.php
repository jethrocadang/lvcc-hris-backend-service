<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Multitenancy\Models\Tenant;

class NewTenantModel extends Tenant
{
    protected $fillable = ['name', 'database'];

    protected $table = 'tenants';
    protected static function booted()
    {
        static::creating(function (NewTenantModel $tenant) {
            // Generate a unique database name
            if (empty($tenant->database)) {
                $tenant->database = 'tenant_' . Str::random(10);
            }
        });

        static::created(function (NewTenantModel $tenant) {
            // Create the new tenant database
            $tenant->createDatabase();
        });

        static::deleting(function (NewTenantModel $tenant) {
            // Drop the tenant database when the tenant is deleted
            $tenant->dropDatabase();
        });
    }

    public function createDatabase()
    {
        DB::statement("CREATE DATABASE `{$this->database}`");
    }

    public function dropDatabase()
    {
        DB::statement("DROP DATABASE IF EXISTS `{$this->database}`");
    }
}
