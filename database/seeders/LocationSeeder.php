<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['name' => 'Ikoyi', 'state' => 'Lagos', 'description' => 'An upscale island district known for waterfront homes and diplomatic residences.'],
            ['name' => 'Victoria Island', 'state' => 'Lagos', 'description' => 'Lagos\' commercial hub, home to corporate offices, hotels, and luxury apartments.'],
            ['name' => 'Lekki', 'state' => 'Lagos', 'description' => 'A fast-growing corridor with new estates, shortlets, and beachfront properties.'],
            ['name' => 'Surulere', 'state' => 'Lagos', 'description' => 'A well-established mainland neighbourhood popular with families and young professionals.'],
            ['name' => 'Ikeja', 'state' => 'Lagos', 'description' => 'The Lagos mainland\'s administrative and business centre with strong rental demand.'],
            ['name' => 'Maitama', 'state' => 'FCT - Abuja', 'description' => 'One of Abuja\'s most prestigious districts, favoured by diplomats and executives.'],
            ['name' => 'Gwarinpa', 'state' => 'FCT - Abuja', 'description' => 'One of Africa\'s largest planned housing estates, popular for affordable family homes.'],
            ['name' => 'Port Harcourt GRA', 'state' => 'Rivers', 'description' => 'A quiet, tree-lined government reservation area favoured by oil and gas professionals.'],
        ];

        foreach ($locations as $location) {
            Location::firstOrCreate(
                ['slug' => Str::slug($location['name'])],
                [
                    'name' => $location['name'],
                    'state' => $location['state'],
                    'description' => $location['description'],
                ]
            );
        }
    }
}
