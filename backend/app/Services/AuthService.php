<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function __construct(private UserRepositoryInterface $userRepo) {}

    public function register(array $data): array
    {
        $user = $this->userRepo->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'phone'    => $data['phone'] ?? null,
            'address'  => $data['address'] ?? null,
        ]);

        $user->assignRole($data['role'] ?? 'client');

        $token = JWTAuth::fromUser($user);

        ActivityLog::log('user_registered', $user->id, User::class, $user->id);

        return [
            'user'  => $user->load('roles'),
            'token' => $token,
        ];
    }

    public function login(string $email, string $password): ?array
    {
        $user = $this->userRepo->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        if (!$user->is_active) {
            return ['error' => 'Account is deactivated. Contact support.'];
        }

        $token = JWTAuth::fromUser($user);

        ActivityLog::log('user_login', $user->id, User::class, $user->id);

        return [
            'user'  => $user->load('roles'),
            'token' => $token,
        ];
    }

    public function logout(): void
    {
        ActivityLog::log('user_logout');
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function refreshToken(): string
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }

    public function me(): User
    {
        return auth()->user()->load('roles');
    }

    public function updateProfile(User $user, array $data): User
    {
        $updateData = array_filter([
            'name'    => $data['name'] ?? null,
            'phone'   => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ], fn($v) => $v !== null);

        if (!empty($data['password'])) {
            $updateData['password'] = $data['password'];
        }

        return $this->userRepo->update($user, $updateData);
    }
}
