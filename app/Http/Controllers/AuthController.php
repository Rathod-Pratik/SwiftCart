<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (! auth()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $request->session()->regenerate();
        $user = $request->user();

        if (! $user->hasVerifiedEmail()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json(['message' => 'Email not verified'], 403);
        }

        return response()->json(['message' => 'Login successful', 'user' => $user]);
    }

    public function signup(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'token' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $validatedData,
            function (User $user, string $password): void {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['email' => [__($status)]]);
        }

        return response()->json(['message' => 'Password reset successfully']);
    }

    public function forgetPassword(Request $request): JsonResponse
    {
        $validatedData = $request->validate(['email' => 'required|string|email']);
        $status = Password::sendResetLink($validatedData);

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages(['email' => [__($status)]]);
        }

        return response()->json(['message' => 'Password reset link sent']);
    }

    public function verifyEmail(EmailVerificationRequest $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json(['message' => 'Email verified successfully']);
    }

    public function resendVerificationEmail(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email sent']);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->authorize('update', $user);

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,'.$user->id,
        ]);

        $user->name = $validatedData['name'] ?? $user->name;
        $user->email = $validatedData['email'] ?? $user->email;

        if (isset($validatedData['email']) && $validatedData['email'] !== $user->getOriginal('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }

    public function getProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json(['user' => $user]);
    }

    public function deleteAccount(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->authorize('delete', $user);
        $user->delete();
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Account deleted successfully']);
    }
}
