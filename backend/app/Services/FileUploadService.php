<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    private const ALLOWED_IMAGE_MIMES = ['image/jpeg', 'image/png', 'image/webp'];
    private const ALLOWED_DOC_MIMES   = ['image/jpeg', 'image/png', 'application/pdf'];
    private const MAX_IMAGE_SIZE      = 5120;  // 5 MB in KB
    private const MAX_DOC_SIZE        = 10240; // 10 MB in KB

    public function uploadVehicleImage(UploadedFile $file): string
    {
        $this->validateFile($file, self::ALLOWED_IMAGE_MIMES, self::MAX_IMAGE_SIZE);
        return $this->store($file, 'vehicles');
    }

    public function uploadDriverLicense(UploadedFile $file): string
    {
        $this->validateFile($file, self::ALLOWED_DOC_MIMES, self::MAX_DOC_SIZE);
        return $this->store($file, 'licenses');
    }

    public function uploadAvatar(UploadedFile $file): string
    {
        $this->validateFile($file, self::ALLOWED_IMAGE_MIMES, self::MAX_IMAGE_SIZE);
        return $this->store($file, 'avatars');
    }

    public function delete(string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function url(string $path): string
    {
        return Storage::disk('public')->url($path);
    }

    private function store(UploadedFile $file, string $directory): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($directory, $filename, 'public');
    }

    private function validateFile(UploadedFile $file, array $allowedMimes, int $maxKb): void
    {
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \InvalidArgumentException(
                'Invalid file type. Allowed: ' . implode(', ', $allowedMimes)
            );
        }

        if ($file->getSize() > $maxKb * 1024) {
            throw new \InvalidArgumentException("File size must not exceed {$maxKb} KB");
        }
    }
}
