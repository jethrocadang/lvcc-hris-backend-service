<?php

namespace App\Models;

use App\Jobs\CreateTenantDatabaseJob;
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
            \Log::info("Dispatching job for: " . $tenant->database);
            // Create the new tenant database
            CreateTenantDatabaseJob::dispatch($tenant)->onQueue(null);        });

    }
}
