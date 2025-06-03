<?php

namespace App\Services\Hris;

use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function getUsers(): Collection
    {
       $users = User::with(['roles', 'permissions'])->get();

        return $users->isNotEmpty()
        ? UserResource::collection($users)->collection
        : collect();
    }

    public function deleteUser(int $id): void
    {
        try {
            // Find the email template by ID
            $user = User::findOrFail($id);

            // Delete the email template
            $user->delete();
        } catch (ModelNotFoundException $e) {
            Log::error('User not found', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            Log::error('User deletion failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}