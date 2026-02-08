<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Listing extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'listing_type',
        'property_type',
        'price',
        'bedrooms',
        'bathrooms',
        'toilets',
        'parking_spaces',
        'area_sqm',
        'address',
        'location_id',
        'agent_id',
        'status',
        'featured',
        'cover_image',
        'cover_thumb',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'featured' => 'boolean',
        ];
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ListingImage::class)->orderBy('sort_order');
    }
}
