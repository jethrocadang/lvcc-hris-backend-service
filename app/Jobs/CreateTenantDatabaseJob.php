<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\NewTenantModel;
use Spatie\Multitenancy\Jobs\NotTenantAware;

class CreateTenantDatabaseJob implements ShouldQueue, NotTenantAware
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tenant;

    /**
     * Create a new job instance.
     */
    /**
     * Create a new job instance for creating a tenant's database.
     *
     * @param NewTenantModel $tenant The tenant model containing information about the new tenant.
     *
     * @return void
     */
    public function __construct(NewTenantModel $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     */
    /**
     * Execute the job to create a new database for the tenant.
     *
     * This method attempts to create a new database for the tenant if it doesn't already exist.
     * It logs the success or failure of the database creation process.
     *
     * @return void
     *
     * @throws \Exception If there's an error during database creation, it's caught and logged.
     */
    public function handle()
    {
        try {
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$this->tenant->database}`");
            \Log::info("Database created: {$this->tenant->database}");
        } catch (\Exception $e) {
            \Log::error("Failed to create database for tenant {$this->tenant->id}: " . $e->getMessage());
        }
    }
}
