<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'is_developer',
        'website',
        'logo_url',
        'logo_thumb',
        'address',
        'bio',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_developer' => 'boolean',
        ];
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }
}
