<?php

namespace App\Support;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploader
{
    public function upload(UploadedFile $file, string $directory, int $thumbWidth, int $thumbHeight): array
    {
        return $this->uploadFromPath($file->getRealPath(), $directory, $thumbWidth, $thumbHeight);
    }

    public function uploadFromPath(string $absolutePath, string $directory, int $thumbWidth, int $thumbHeight): array
    {
        $result = Cloudinary::uploadApi()->upload($absolutePath, [
            'folder' => trim($directory, '/'),
        ]);

        $path = $result['secure_url'];

        return ['path' => $path, 'thumb' => self::thumbUrl($path, $thumbWidth, $thumbHeight)];
    }

    public static function thumbUrl(string $path, int $width, int $height): string
    {
        return str_replace(
            '/upload/',
            "/upload/c_fill,w_{$width},h_{$height},q_auto,f_auto/",
            $path
        );
    }

    public function delete(?string $path, ?string $thumb = null): void
    {
        if (! $path) {
            return;
        }

        if (str_starts_with($path, 'http')) {
            $publicId = $this->publicIdFromUrl($path);

            if ($publicId) {
                Cloudinary::uploadApi()->destroy($publicId);
            }

            return;
        }

        $disk = Storage::disk('public');

        if ($disk->exists($path)) {
            $disk->delete($path);
        }

        if ($thumb && $disk->exists($thumb)) {
            $disk->delete($thumb);
        }
    }

    private function publicIdFromUrl(string $url): ?string
    {
        if (! preg_match('#/upload/v\d+/(.+)\.[a-zA-Z0-9]+$#', $url, $matches)) {
            return null;
        }

        return $matches[1];
    }
}
