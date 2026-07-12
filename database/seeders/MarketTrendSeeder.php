<?php

namespace Database\Seeders;

use App\Models\MarketTrend;
use Illuminate\Database\Seeder;

class MarketTrendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trends = [
            [
                'title' => 'Average prices',
                'metric' => '+8.2% YoY',
                'description' => 'Average sale prices in Lagos prime districts have risen steadily, driven by demand for waterfront and gated-estate properties.',
                'sort_order' => 1,
            ],
            [
                'title' => 'Demand hotspots',
                'metric' => 'Lekki, Ikoyi, Maitama',
                'description' => 'Lekki, Ikoyi, and Maitama continue to record the highest search and enquiry volumes across sale and rent listings.',
                'sort_order' => 2,
            ],
            [
                'title' => 'Rental yields',
                'metric' => '6.5% avg',
                'description' => 'Serviced apartments and shortlets in Victoria Island and Ikoyi are delivering the strongest rental yields nationwide.',
                'sort_order' => 3,
            ],
            [
                'title' => 'Shortlet demand',
                'metric' => '+21% bookings',
                'description' => 'Short-term rental bookings have grown sharply as corporate travel and weekend getaways rebound in major cities.',
                'sort_order' => 4,
            ],
            [
                'title' => 'Land appreciation',
                'metric' => '+11.4% YoY',
                'description' => 'Residential land in emerging estates around Gwarinpa and Ikeja continues to appreciate ahead of infrastructure completion.',
                'sort_order' => 5,
            ],
        ];

        foreach ($trends as $trend) {
            MarketTrend::firstOrCreate(
                ['title' => $trend['title']],
                [
                    'metric' => $trend['metric'],
                    'description' => $trend['description'],
                    'sort_order' => $trend['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
