<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUpload
{
    /**
     * Upload a file (PDF or Image) and return the path.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param array $allowedMimes
     * @return string|null
     */
    public static function upload(UploadedFile $file, string $folder = 'uploads', array $allowedMimes = []): ?string
    {
        // Default allowed mimes for PDF and Images
        if (empty($allowedMimes)) {
            $allowedMimes = [
                'application/pdf',
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp',
                'image/svg+xml',
            ];
        }

        // Validate file type
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('Invalid file type. Only PDF and Image files are allowed.');
        }

        // Validate file size (max 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            throw new \Exception('File size exceeds 10MB limit.');
        }

        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Store file
        $path = $file->storeAs($folder, $filename, 'public');

        return $path;
    }

    /**
     * Delete a file from storage.
     *
     * @param string $path
     * @return bool
     */
    public static function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * Get file URL.
     *
     * @param string $path
     * @return string
     */
    public static function url(string $path): string
    {
        return Storage::disk('public')->url($path);
    }

    /**
     * Check if file is an image.
     *
     * @param string $mimeType
     * @return bool
     */
    public static function isImage(string $mimeType): bool
    {
        return str_starts_with($mimeType, 'image/');
    }

    /**
     * Check if file is a PDF.
     *
     * @param string $mimeType
     * @return bool
     */
    public static function isPdf(string $mimeType): bool
    {
        return $mimeType === 'application/pdf';
    }
}

