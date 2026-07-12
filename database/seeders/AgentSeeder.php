<?php

namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agents = [
            [
                'name' => 'Adaeze Okafor',
                'email' => 'adaeze.okafor@summitestates.ng',
                'phone' => '+234 803 555 0142',
                'company' => 'Summit Estates Ltd',
                'is_developer' => false,
                'website' => 'https://summitestates.ng',
                'address' => 'Ikoyi, Lagos',
                'bio' => 'Luxury homes and waterfront property specialist with over a decade of experience in Lagos.',
                'status' => 'active',
            ],
            [
                'name' => 'Tunde Bakare',
                'email' => 'tunde.bakare@capitalliving.ng',
                'phone' => '+234 805 555 0198',
                'company' => 'Capital Living Realty',
                'is_developer' => false,
                'website' => 'https://capitalliving.ng',
                'address' => 'Maitama, Abuja',
                'bio' => 'Focused on family homes and long-term rentals across the FCT.',
                'status' => 'active',
            ],
            [
                'name' => 'Ibinabo Wokoma',
                'email' => 'ibinabo.wokoma@harborline.ng',
                'phone' => '+234 807 555 0173',
                'company' => 'Harborline Realty',
                'is_developer' => false,
                'website' => 'https://harborline.ng',
                'address' => 'Port Harcourt GRA, Rivers',
                'bio' => 'Commercial and industrial property broker serving the Niger Delta region.',
                'status' => 'active',
            ],
            [
                'name' => 'Chiamaka Nwosu',
                'email' => 'chiamaka.nwosu@lekkishores.ng',
                'phone' => '+234 809 555 0211',
                'company' => 'Lekki Shores Developments',
                'is_developer' => true,
                'website' => 'https://lekkishores.ng',
                'address' => 'Lekki, Lagos',
                'bio' => 'Developer of gated residential estates and serviced shortlet apartments along the Lekki corridor.',
                'status' => 'active',
            ],
            [
                'name' => 'Emeka Umeh',
                'email' => 'emeka.umeh@gwarinparealty.ng',
                'phone' => '+234 802 555 0186',
                'company' => 'Gwarinpa Realty Partners',
                'is_developer' => false,
                'website' => 'https://gwarinparealty.ng',
                'address' => 'Gwarinpa, Abuja',
                'bio' => 'Independent agent specialising in affordable housing across the Gwarinpa estate.',
                'status' => 'active',
            ],
            [
                'name' => 'Folasade Adeyemi',
                'email' => 'folasade.adeyemi@primelandng.com',
                'phone' => '+234 810 555 0224',
                'company' => 'Prime Land Developments',
                'is_developer' => true,
                'website' => 'https://primelandng.com',
                'address' => 'Ikeja, Lagos',
                'bio' => 'Land banking and mixed-use commercial developer active across mainland Lagos.',
                'status' => 'active',
            ],
        ];

        foreach ($agents as $agent) {
            Agent::firstOrCreate(
                ['email' => $agent['email']],
                $agent
            );
        }
    }
}
