<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PasswordController extends Controller
{
    public function createPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:8|confirmed',
            ]);

            $user = User::where('email', $request->email)->firstOrFail();

            if ($user->password) {
                return response()->json(['error' => 'Password already set.'], 400);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            Log::info('Password created for user: ' . $user->email);

            return response()->json([
                'message' => 'Password set successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Setting password failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to set password', 'message' => $e->getMessage()], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ]);

            $user = User::where('email', $request->email)->firstOrFail();

            // Check if the current password is correct
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['error' => 'Current password is incorrect.'], 401);
            }

            // Prevent using the same password
            if (Hash::check($request->new_password, $user->password)) {
                return response()->json(['error' => 'New password cannot be the same as the old password.'], 400);
            }

            // Update and save new password
            $user->password = Hash::make($request->new_password);
            $user->save();

            Log::info('Password updated for user: ' . $user->email);

            return response()->json([
                'message' => 'Password updated successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Updating password failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update password', 'message' => $e->getMessage()], 500);
        }
    }
}
