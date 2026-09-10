<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\S3Service;
use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(protected S3Service $s3Service) {}

    /**
     * Handle user login.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email address cannot exceed 255 characters.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
        ]);

        if (! Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $request->session()->regenerate();
        /** @var User $user */
        $user = $request->user();

        if (! $user->hasVerifiedEmail()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json(['message' => 'Email not verified'], 403);
        }

        return response()->json(['message' => 'Login successful', 'user' => $user]);
    }

    /**
     * Handle user registration.
     */
    public function signup(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|min:2|max:50',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:64|confirmed',
        ], [
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at least 2 characters.',
            'name.max' => 'Name cannot exceed 50 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email address cannot exceed 255 characters.',
            'email.unique' => 'An account with this email already exists.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.max' => 'Password cannot exceed 64 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ]);

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Send password reset link to user email.
     */
    public function forgetPassword(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email address cannot exceed 255 characters.',
        ]);

        $status = Password::sendResetLink($validatedData);

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages(['email' => [__($status)]]);
        }

        return response()->json(['message' => __($status)]);
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|max:64|confirmed',
        ], [
            'token.required' => 'Reset token is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email address cannot exceed 255 characters.',
            'password.required' => 'New password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.max' => 'Password cannot exceed 64 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password): void {
                $user->forceFill(['password' => $password])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['email' => [__($status)]]);
        }

        return response()->json(['message' => __($status)]);
    }

    /**
     * Verify email address.
     */
    public function verifyEmail(EmailVerificationRequest $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['message' => 'Email verified successfully']);
    }

    /**
     * Resend email verification notification.
     */
    public function resendVerificationEmail(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email sent']);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            if (! $user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            Gate::authorize('update', $user);

            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|min:2|max:50',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,'.$user->id,
                'phone' => 'sometimes|nullable|string|min:10|max:15',
                'address' => 'sometimes|nullable|string|min:5|max:255',
                'image' => 'required|image|max:2048',
                'password' => 'sometimes|required|string|min:8|max:64|confirmed',
            ], [
                'name.required' => 'Name is required.',
                'name.min' => 'Name must be at least 2 characters.',
                'name.max' => 'Name cannot exceed 50 characters.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.max' => 'Email address cannot exceed 255 characters.',
                'email.unique' => 'This email is already in use by another account.',
                'phone.min' => 'Phone number must be at least 10 digits.',
                'phone.max' => 'Phone number cannot exceed 15 digits.',
                'address.min' => 'Address must be at least 5 characters.',
                'address.max' => 'Address cannot exceed 255 characters.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 8 characters long.',
                'password.max' => 'Password cannot exceed 64 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
            ]);

            $image = $request->file('image');
            $key = $this->s3Service->generateKey('profile-images', $user->id, $image->getClientOriginalName());
            $uploaded = $this->s3Service->uploadFromServer(
                $key,
                file_get_contents($image->getRealPath())
            );

            if (! $uploaded) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload image',
                ], 500);
            }
            $validatedData['image'] = $key;
            $validatedData['password'] = $validatedData['password'] ?? $user->password;
            $user = $user->fill($validatedData);
            $user->save();

            return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error occurred while updating profile', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get authenticated user profile.
     */
    public function getProfile(Request $request): JsonResponse
    {
        try {

            $user = $request->user();

            if (! $user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            if ($user->isVendor()) {
                return response()->json(['user' => $user->load('bankDetail')]);
            }

            return response()->json(['user' => $user]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error occurred while fetching user profile'], 500);
        }
    }

    /**
     * Update vendor bank details.
     */
    public function updateBankDetails(Request $request): JsonResponse
    {

        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (! $user->isVendor()) {
            return response()->json(['message' => 'Only vendors can update bank details'], 403);
        }

        $validatedData = $request->validate([
            'account_holder_name' => 'required|string|min:2|max:100',
            'company_name' => 'sometimes|nullable|string|min:2|max:100',
            'bank_name' => 'required|string|min:2|max:100',
            'account_number' => 'required|string|min:8|max:30',
            'account_type' => 'required|string|min:2|max:50',
            'ifsc_code' => 'required|string|min:4|max:20',
            'branch_name' => 'required|string|min:2|max:100',
        ], [
            'account_holder_name.required' => 'Account holder name is required.',
            'account_holder_name.min' => 'Account holder name must be at least 2 characters.',
            'account_holder_name.max' => 'Account holder name cannot exceed 100 characters.',
            'company_name.min' => 'Company name must be at least 2 characters.',
            'company_name.max' => 'Company name cannot exceed 100 characters.',
            'bank_name.required' => 'Bank name is required.',
            'bank_name.min' => 'Bank name must be at least 2 characters.',
            'bank_name.max' => 'Bank name cannot exceed 100 characters.',
            'account_number.required' => 'Account number is required.',
            'account_number.min' => 'Account number must be at least 8 digits.',
            'account_number.max' => 'Account number cannot exceed 30 digits.',
            'account_type.required' => 'Account type is required.',
            'account_type.min' => 'Account type must be at least 2 characters.',
            'account_type.max' => 'Account type cannot exceed 50 characters.',
            'ifsc_code.required' => 'IFSC code is required.',
            'ifsc_code.min' => 'IFSC code must be at least 4 characters.',
            'ifsc_code.max' => 'IFSC code cannot exceed 20 characters.',
            'branch_name.required' => 'Branch name is required.',
            'branch_name.min' => 'Branch name must be at least 2 characters.',
            'branch_name.max' => 'Branch name cannot exceed 100 characters.',
        ]);

        $bankDetail = $user->bankDetail()->updateOrCreate(
            ['vendor_id' => $user->id],
            $validatedData
        );

        return response()->json([
            'message' => 'Bank details updated successfully',
            'bank_detail' => $bankDetail,
        ]);
    }

    /**
     * Delete user account.
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        /** @var User $user */
        Gate::authorize('delete', $user);

        $request->validate([
            'password' => 'required|string|current_password',
        ], [
            'password.required' => 'Password is required to delete your account.',
            'password.current_password' => 'The provided password does not match your current password.',
        ]);

        $user->delete(false);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Account deleted successfully']);
    }

    public function getUser(Request $request): JsonResponse
    {

        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (! $user->hasRole('user')) {
            return response()->json(['message' => 'User is not a regular user'], 403);
        }

        return response()->json(['user' => $user]);
    }

    public function getUserById(int $id): JsonResponse
    {

        $user = User::query()->whereKey($id)->first();
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['user' => $user]);
    }

    public function getVendor(Request $request): JsonResponse
    {

        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (! $user->isVendor()) {
            return response()->json(['message' => 'User is not a vendor'], 403);
        }

        return response()->json(['vendor' => $user]);
    }

    public function getVendorById(int $id): JsonResponse
    {

        $user = User::query()->whereKey($id)->first();
        if (! $user) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        if (! $user->isVendor()) {
            return response()->json(['message' => 'User is not a vendor'], 403);
        }

        return response()->json(['vendor' => $user]);
    }
}
