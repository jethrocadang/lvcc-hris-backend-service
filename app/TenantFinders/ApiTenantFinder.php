<?php

namespace App\TenantFinders;

use App\Models\NewTenantModel;
use Illuminate\Http\Request;
use Spatie\Multitenancy\TenantFinder\TenantFinder;
use Spatie\Multitenancy\Contracts\IsTenant;

class ApiTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?IsTenant
    {
        // Get tenant ID from request headers
        $tenantId = $request->header('X-Tenant-ID');

        if (!$tenantId) {
            return null; // No tenant provided
        }

        return NewTenantModel::find($tenantId);
    }
}


