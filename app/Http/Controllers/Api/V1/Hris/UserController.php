<?php

namespace App\Http\Controllers\Api\V1\Hris;

use App\Http\Controllers\Controller;

use App\Services\Hris\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\ApiResponse;
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
            // Find the email template by ID
            $this->userService->deleteUser($id);

            // Return success response
            return $this->successResponse('User deleted successfully!', []);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete department!', ['error' => $e->getMessage()], 500);
        }
    }
}
