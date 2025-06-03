<?php

namespace App\Http\Controllers\Api\V1\Hris;

use App\Http\Controllers\Controller;
use App\Services\Hris\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;

class UserController extends Controller
{
    use ApiResponse;

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $userService = $this->userService->getUsers();

        return $userService->isNotEmpty()
            ? $this->successResponse('Users retrieved successfully!', $userService)
            : $this->errorResponse('No users found', [], 404);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->userService->deleteUser($id);
            return $this->successResponse('User deleted successfully!', []);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete user!', ['error' => $e->getMessage()], 500);
        }
    }

    public function attachRole(Request $request, $id)
    {
        $request->validate(['role' => 'required|string']);
        $user = User::findOrFail($id);

        $this->userService->attachRole($user, $request->role);
        return $this->successResponse('Role attached successfully.',
        [
            'userId' => $user->id,
            'attachedRole' => $request->role
        ]);
    }

    public function detachRole(Request $request, $id)
    {
        $request->validate(['role' => 'required|string']);
        $user = User::findOrFail($id);

        $this->userService->detachRole($user, $request->role);
        return $this->successResponse('Role detached successfully.',
        [
            'userId' => $user->id,
            'detachedRole' => $request->role
        ]);
    }

    public function attachPermission(Request $request, $id)
    {
        $request->validate([
            'permission' => 'required|array',
            'permission.*' => 'string'
        ]);

        $user = User::findOrFail($id);

        $this->userService->attachPermissions($user, $request->permission);

        return $this->successResponse('Permissions attached successfully.', 
        [
            'userId' => $user->id,
            'attachedPermissions' => $request->permission
        ]);
    }

    public function detachPermission(Request $request, $id)
    {
        $request->validate([
            'permission' => 'required|array',
            'permission.*' => 'string'
        ]);

        $user = User::findOrFail($id);

        $this->userService->detachPermissions($user, $request->permission);

        return $this->successResponse('Permissions detached successfully.',
        [
            'userId' => $user->id,
            'detachedPermissions' => $request->permission
        ]);
    }
}
