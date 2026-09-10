<?php

namespace App\Services;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class S3Service
{
    protected FilesystemAdapter $disk;

    public function __construct()
    {
        $this->disk = Storage::disk('s3');
    }

    /**
     * Generate a unique S3 key for a new upload, namespaced under
     * a folder and the uploading user's ID.
     */
    public function generateKey(string $folder, int $userId, string $fileName): string
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        return "{$folder}/{$userId}/".Str::uuid().".{$extension}";
    }

    /**
     * Generate a presigned PUT URL the client uploads directly to.
     */
    public function generateUploadUrl(string $key, string $contentType, int $expiryMinutes = 5): array
    {
        return $this->disk->temporaryUploadUrl(
            $key,
            now()->addMinutes($expiryMinutes),
            ['ContentType' => $contentType]
        );
    }

    /**
     * Generate a presigned GET URL to view/download a private file.
     */
    public function generateDownloadUrl(string $key, int $expiryMinutes = 10): ?string
    {
        if (! $this->disk->exists($key)) {
            return null;
        }

        return $this->disk->temporaryUrl($key, now()->addMinutes($expiryMinutes));
    }

    /**
     * Upload a file directly from the server (e.g. generated PDFs, reports).
     */
    public function uploadFromServer(string $key, string $contents, string $visibility = 'private'): bool
    {

        return $this->disk->put($key, $contents, $visibility);
    }

    /**
     * Check if a file exists on S3.
     */
    public function exists(string $key): bool
    {
        return $this->disk->exists($key);
    }

    /**
     * Delete a file from S3.
     */
    public function delete(string $key): bool
    {
        return $this->disk->delete($key);
    }
}
