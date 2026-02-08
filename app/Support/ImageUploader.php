<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploader
{
    public function upload(UploadedFile $file, string $directory, int $thumbWidth, int $thumbHeight): array
    {
        $disk = Storage::disk('public');
        $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $path = trim($directory, '/').'/'.$filename;

        $disk->putFileAs($directory, $file, $filename);

        if (! extension_loaded('gd') || ! function_exists('gd_info')) {
            return ['path' => $path, 'thumb' => null];
        }

        $thumbFilename = Str::uuid()->toString().'.jpg';
        $thumbPath = trim($directory, '/').'/thumbs/'.$thumbFilename;

        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath());
        $image->cover($thumbWidth, $thumbHeight);

        $disk->put($thumbPath, (string) $image->toJpeg(85));

        return ['path' => $path, 'thumb' => $thumbPath];
    }

    public function delete(?string $path, ?string $thumb = null): void
    {
        $disk = Storage::disk('public');

        if ($path && $disk->exists($path)) {
            $disk->delete($path);
        }

        if ($thumb && $disk->exists($thumb)) {
            $disk->delete($thumb);
        }
    }
}
