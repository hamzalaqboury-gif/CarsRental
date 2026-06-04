<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(private AuthService $authService) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return $this->created([
            'user'  => $result['user'],
            'token' => $result['token'],
        ], 'Account created successfully.');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->email, $request->password);

        if (!$result) {
            return $this->error('Invalid credentials.', 401);
        }

        if (isset($result['error'])) {
            return $this->forbidden($result['error']);
        }

        return $this->success([
            'user'  => $result['user'],
            'token' => $result['token'],
        ], 'Login successful.');
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return $this->success(null, 'Logged out successfully.');
    }

    public function refresh(): JsonResponse
    {
        $token = $this->authService->refreshToken();
        return $this->success(['token' => $token], 'Token refreshed.');
    }

    public function me(): JsonResponse
    {
        return $this->success($this->authService->me());
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $request->validate([
            'name'             => ['sometimes', 'string', 'max:255'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'address'          => ['nullable', 'string', 'max:500'],
            'current_password' => ['required_with:password', 'string'],
            'password'         => ['sometimes', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        if ($request->has('password') && !\Hash::check($request->current_password, $user->password)) {
            return $this->error('Current password is incorrect.', 422);
        }

        $updated = $this->authService->updateProfile($user, $request->validated());

        return $this->success($updated, 'Profile updated successfully.');
    }
}
