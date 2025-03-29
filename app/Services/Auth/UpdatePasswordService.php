<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UpdatePasswordService
{
    /**
     * Updates the user's password after verifying the current password.
     *
     * @param string $email User's email
     * @param string $currentPassword Current password provided by the user
     * @param string $newPassword New password to be set
     * @return array Response indicating success or failure
     */
    public function updatePassword(string $email, string $currentPassword, string $newPassword): array
    {
        try {
            // Find the user by email throws an exception if not found
            $user = User::where('email', $email)->firstOrFail();


            // Check if the current password is correct
            if (!Hash::check($currentPassword, $user->password)) {
                return ['success' => false, 'message' => 'Current password is incorrect.'];
            }

            // Prevent using the same password again
            if (Hash::check($newPassword, $user->password)) {
                return ['success' => false, 'message' => 'New password cannot be the same as the old password.'];
            }

            // Update and save the new password
            $user->password = Hash::make($newPassword);
            $user->save();

            // Log the password update
            Log::info('Password updated for user: ' . $user->email);

            return ['success' => true, 'message' => 'Password updated successfully.'];

        } catch (\Exception $e) {
            Log::error('Updating password failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to update password.'];
        }
    }
}
