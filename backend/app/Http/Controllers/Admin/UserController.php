<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(private UserRepositoryInterface $userRepo) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'role', 'is_active']);
        $users   = $this->userRepo->paginate($request->integer('per_page', 15), $filters);

        return $this->paginated($users, 'Users retrieved.');
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userRepo->findById($id);

        if (!$user) {
            return $this->notFound('User not found.');
        }

        return $this->success($user->load(['roles', 'reservations']));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::min(8)->letters()->numbers()],
            'role'     => ['required', 'in:super-admin,admin,manager,client'],
            'phone'    => ['nullable', 'string', 'max:20'],
        ]);

        $user = $this->userRepo->create($data);
        $user->assignRole($data['role']);

        return $this->created($user->load('roles'), 'User created.');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $user = $this->userRepo->findById($id);

        if (!$user) {
            return $this->notFound('User not found.');
        }

        $data = $request->validate([
            'name'      => ['sometimes', 'string', 'max:255'],
            'email'     => ['sometimes', 'email', "unique:users,email,{$id}"],
            'role'      => ['sometimes', 'in:super-admin,admin,manager,client'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if (isset($data['role'])) {
            $user->syncRoles([$data['role']]);
            unset($data['role']);
        }

        $updated = $this->userRepo->update($user, $data);

        return $this->success($updated->load('roles'), 'User updated.');
    }

    public function destroy(int $id): JsonResponse
    {
        if ($id === auth()->id()) {
            return $this->error('Cannot delete your own account.', 422);
        }

        $user = $this->userRepo->findById($id);

        if (!$user) {
            return $this->notFound('User not found.');
        }

        $this->userRepo->delete($user);

        return $this->success(null, 'User deleted.');
    }

    public function toggleStatus(int $id): JsonResponse
    {
        $user = $this->userRepo->findById($id);

        if (!$user) {
            return $this->notFound('User not found.');
        }

        $this->userRepo->update($user, ['is_active' => !$user->is_active]);

        return $this->success(
            $user->fresh(),
            $user->is_active ? 'User activated.' : 'User deactivated.'
        );
    }
}
