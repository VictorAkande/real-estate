<?php

namespace App\Console\Commands;

use App\Models\Agent;
use App\Models\AreaGuide;
use App\Models\ContentPage;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\Post;
use App\Support\ImageUploader;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MigrateImagesToCloudinary extends Command
{
    protected $signature = 'images:migrate-to-cloudinary
        {--dry-run : List what would happen without uploading, updating, or deleting anything}
        {--keep-local : Do not delete local files after a successful upload}';

    protected $description = 'Upload existing locally-stored images to Cloudinary and repoint database records to the new URLs.';

    private ImageUploader $uploader;

    public function __construct()
    {
        parent::__construct();

        $this->uploader = new ImageUploader();
    }

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $keepLocal = (bool) $this->option('keep-local');

        if ($dryRun) {
            $this->warn('Dry run: no uploads, database writes, or deletions will happen.');
        }

        $jobs = [
            [Listing::query(), 'cover_image', 'cover_thumb', 'listings', 640, 420],
            [ListingImage::query(), 'image_path', 'thumb_path', 'listings/gallery', 900, 600],
            [Post::query(), 'cover_image', 'cover_thumb', 'posts', 900, 600],
            [AreaGuide::query(), 'cover_image', 'cover_thumb', 'area-guides', 900, 600],
            [Agent::query(), 'logo_url', 'logo_thumb', 'agents', 240, 240],
            [ContentPage::query(), 'image_path', null, 'content', 1000, 700],
        ];

        $migrated = 0;
        $missing = 0;
        $alreadyDone = 0;
        $empty = 0;

        foreach ($jobs as [$query, $pathField, $thumbField, $folder, $width, $height]) {
            foreach ($query->cursor() as $model) {
                $result = $this->migrateField($model, $pathField, $thumbField, $folder, $width, $height, $dryRun, $keepLocal);

                match ($result) {
                    'migrated' => $migrated++,
                    'missing' => $missing++,
                    'empty' => $empty++,
                    default => $alreadyDone++,
                };
            }
        }

        $this->info("Migrated: {$migrated}, already on Cloudinary: {$alreadyDone}, no image set: {$empty}, missing local file: {$missing}");

        return self::SUCCESS;
    }

    private function migrateField(
        Model $model,
        string $pathField,
        ?string $thumbField,
        string $folder,
        int $width,
        int $height,
        bool $dryRun,
        bool $keepLocal
    ): string {
        $path = $model->{$pathField};
        $label = class_basename($model)."#{$model->getKey()}";

        if (! $path) {
            return 'empty';
        }

        if (str_starts_with($path, 'http')) {
            return 'already';
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            $this->warn("Missing local file for {$label}: {$path}");

            return 'missing';
        }

        if ($dryRun) {
            $this->line("Would migrate {$label}: {$path}");

            return 'migrated';
        }

        $oldThumb = $thumbField ? $model->{$thumbField} : null;

        $upload = $this->uploader->uploadFromPath($disk->path($path), $folder, $width, $height);

        $update = [$pathField => $upload['path']];

        if ($thumbField) {
            $update[$thumbField] = $upload['thumb'];
        }

        $model->update($update);

        if (! $keepLocal) {
            $disk->delete($path);

            if ($oldThumb && ! str_starts_with($oldThumb, 'http') && $disk->exists($oldThumb)) {
                $disk->delete($oldThumb);
            }
        }

        $this->line("Migrated {$label}: {$path} -> {$upload['path']}");

        return 'migrated';
    }
}
