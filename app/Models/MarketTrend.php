<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketTrend extends Model
{
    protected $fillable = [
        'title',
        'metric',
        'description',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
