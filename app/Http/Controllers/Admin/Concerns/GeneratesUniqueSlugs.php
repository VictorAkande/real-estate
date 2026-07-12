<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Support\Str;

trait GeneratesUniqueSlugs
{
    protected function uniqueSlug(string $model, string $source, string $slugColumn = 'slug', ?int $ignoreId = null): string
    {
        $base = Str::slug($source);
        $slug = $base;
        $counter = 1;

        while (
            $model::where($slugColumn, $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
