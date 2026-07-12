<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaGuide extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'state',
        'summary',
        'body',
        'cover_image',
        'cover_thumb',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
