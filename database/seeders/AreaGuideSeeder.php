<?php

namespace Database\Seeders;

use App\Models\AreaGuide;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AreaGuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guides = [
            [
                'name' => 'Ikoyi',
                'state' => 'Lagos',
                'summary' => 'An upscale island district known for waterfront homes, embassies, and Lagos\' most exclusive addresses.',
                'body' => "Ikoyi sits at the heart of Lagos Island and remains the city's benchmark for premium residential and diplomatic real estate.\n\nRental expectations: expect to pay significantly above the Lagos average for apartments and detached houses, with serviced apartments commanding a further premium. Family homes near Bourdillon and Parkview are especially sought after.\n\nLifestyle and amenities: residents have easy access to private clubs, international schools, fine dining, and waterfront leisure spots. Security is generally strong given the concentration of diplomatic missions and gated compounds.",
            ],
            [
                'name' => 'Lekki',
                'state' => 'Lagos',
                'summary' => 'A fast-growing corridor with new estates, shortlets, beachfront properties, and strong infrastructure investment.',
                'body' => "Lekki has grown from a quiet coastal suburb into one of Lagos' most dynamic residential and commercial corridors.\n\nRental expectations: pricing varies widely by phase, with Lekki Phase 1 commanding premium rents closer to Victoria Island levels, while areas further along the expressway remain comparatively affordable.\n\nLifestyle and amenities: the area is home to a growing number of shopping malls, beach clubs, and serviced estates, with ongoing road and bridge projects continuing to improve connectivity to the rest of the city.",
            ],
            [
                'name' => 'Maitama',
                'state' => 'FCT - Abuja',
                'summary' => 'One of Abuja\'s most prestigious districts, favoured by diplomats, senior civil servants, and executives.',
                'body' => "Maitama is widely regarded as Abuja's premier residential district, characterised by wide streets, mature landscaping, and low-density housing.\n\nRental expectations: annual rents here rank among the highest in the FCT, reflecting the district's proximity to government institutions and embassies. Detached houses and duplexes dominate the housing stock.\n\nLifestyle and amenities: residents enjoy proximity to top private schools, upscale restaurants, and well-maintained public spaces, with generally reliable infrastructure compared to other parts of the city.",
            ],
            [
                'name' => 'Gwarinpa',
                'state' => 'FCT - Abuja',
                'summary' => 'One of Africa\'s largest planned housing estates, popular for affordable, well-organised family homes.',
                'body' => "Gwarinpa was developed as a large-scale planned estate and remains one of Abuja's most popular middle-income residential areas.\n\nRental expectations: rents are considerably more accessible than Maitama or Asokoro, making it a preferred choice for young families and professionals commuting into central Abuja.\n\nLifestyle and amenities: the estate benefits from a grid-like street layout, numerous schools and places of worship, and a strong sense of community, though some inner sections still await full road and drainage completion.",
            ],
            [
                'name' => 'Port Harcourt GRA',
                'state' => 'Rivers',
                'summary' => 'A quiet, tree-lined government reservation area favoured by oil and gas professionals and senior executives.',
                'body' => "Port Harcourt's Government Reservation Area (GRA) has long been the city's most sought-after residential zone, prized for its layout and relative calm.\n\nRental expectations: demand and pricing are closely tied to activity in the oil and gas sector, with company-let properties often setting the benchmark for rents in GRA Phases 1 and 2.\n\nLifestyle and amenities: residents have access to established private schools, hospitals, and social clubs, with tree-lined streets and larger plot sizes than most other parts of the city.",
            ],
        ];

        foreach ($guides as $guide) {
            AreaGuide::firstOrCreate(
                ['slug' => Str::slug($guide['name'])],
                [
                    'name' => $guide['name'],
                    'state' => $guide['state'],
                    'summary' => $guide['summary'],
                    'body' => $guide['body'],
                    'is_active' => true,
                ]
            );
        }
    }
}
