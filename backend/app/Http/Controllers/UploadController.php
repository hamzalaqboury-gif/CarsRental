<?php

namespace App\Http\Controllers;

use App\Services\FileUploadService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    use ApiResponse;

    public function __construct(private FileUploadService $fileService) {}

    public function uploadDriverLicense(Request $request): JsonResponse
    {
        $request->validate([
            'driver_license' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
        ]);

        try {
            $path = $this->fileService->uploadDriverLicense($request->file('driver_license'));

            auth()->user()->update(['driver_license' => $path]);

            return $this->success([
                'path' => $path,
                'url'  => $this->fileService->url($path),
            ], 'Driver license uploaded successfully.');
        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ]);

        try {
            $user = auth()->user();

            if ($user->avatar) {
                $this->fileService->delete($user->avatar);
            }

            $path = $this->fileService->uploadAvatar($request->file('avatar'));
            $user->update(['avatar' => $path]);

            return $this->success([
                'path' => $path,
                'url'  => $this->fileService->url($path),
            ], 'Avatar uploaded successfully.');
        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage(), 422);
        }
    }
}
