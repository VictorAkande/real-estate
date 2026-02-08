<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = [
        'name',
        'state',
        'slug',
        'description',
    ];

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }
}
