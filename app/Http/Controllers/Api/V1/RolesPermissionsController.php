<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsController extends Controller
{
    public function index()
    {
        return response()->json([
            'roles' => Role::orderBy('id')->get(['id', 'name']),
            'permissions' => Permission::orderBy('id')->get(['id', 'name']),
        ]);
    }
}
