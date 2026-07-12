<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Top Places to Invest in Lagos Real Estate in 2026',
                'excerpt' => 'From Lekki to Ikeja, here is where property investors are finding the strongest returns this year.',
                'body' => "Lagos remains Nigeria's most active property market, and 2026 has brought fresh momentum to a handful of districts.\n\nLekki continues to lead new development activity thanks to ongoing road and bridge infrastructure, while Ikeja is seeing renewed interest from investors drawn to its central location and steady rental demand. Ikoyi and Victoria Island remain the top choices for luxury buyers seeking waterfront properties with strong long-term appreciation.\n\nFor investors working with a tighter budget, emerging corridors around the Lekki-Epe expressway offer land at a fraction of prime prices, with appreciation expected to accelerate as infrastructure projects complete over the next few years.",
            ],
            [
                'title' => 'A Practical Guide to Buying Your First Home in Nigeria',
                'excerpt' => 'What every first-time buyer should check before signing a purchase agreement.',
                'body' => "Buying your first property is exciting, but it pays to slow down and verify the essentials before committing funds.\n\nStart with the title: confirm whether the property has a Certificate of Occupancy, Governor's Consent, or is still under a Deed of Assignment, and work with a solicitor to carry out a proper title search at the land registry. Next, physically inspect the property and confirm there are no encumbrances, disputes, or government acquisition notices attached to the land.\n\nFinally, budget beyond the purchase price itself. Legal fees, agency commission, and statutory charges such as governor's consent and stamp duty typically add 10-15% to the total cost of a transaction.",
            ],
            [
                'title' => 'Shortlet vs. Long-Term Rental: Which Should You List?',
                'excerpt' => 'A breakdown of the returns, effort, and risks of each rental strategy for property owners.',
                'body' => "Property owners increasingly ask whether a shortlet or a traditional annual rental delivers a better return.\n\nShortlets generally command a significantly higher effective rent per night, especially in high-demand districts like Ikoyi and Lekki, but require active management: cleaning, guest communication, and marketing across booking platforms. Vacancy risk is also higher, particularly outside peak travel seasons.\n\nLong-term rentals offer predictable, lower-effort income with a single annual payment upfront, which many landlords still prefer for its simplicity. The right choice often comes down to how hands-on you want to be and how much liquidity you need from the property each year.",
            ],
            [
                'title' => 'Understanding Rental Yields Across Nigerian Cities',
                'excerpt' => 'How Lagos, Abuja, and Port Harcourt compare when it comes to rental income potential.',
                'body' => "Rental yield, the annual rent as a percentage of a property's value, varies considerably across Nigeria's major cities.\n\nLagos serviced apartments in Victoria Island and Ikoyi typically deliver yields in the 6-8% range, supported by strong demand from expatriates and corporate tenants. Abuja's Maitama and Asokoro districts trend slightly lower due to higher entry prices, but offer more stable, lower-turnover tenancies.\n\nPort Harcourt, driven by oil and gas sector demand, can produce some of the highest yields nationally for well-located properties near company estates, though the market is more sensitive to sector-specific economic cycles.",
            ],
            [
                'title' => 'Five Questions to Ask Before Hiring a Property Agent',
                'excerpt' => 'Choosing the right agent can make or break your buying, selling, or renting experience.',
                'body' => "A good property agent saves you time and protects you from costly mistakes, but not all agents operate the same way.\n\nAsk how long they have operated in the specific neighbourhood you're targeting, and request references from recent clients. Confirm whether they are affiliated with a registered estate surveying and valuation firm or professional body, and ask directly about their commission structure before any viewing takes place.\n\nFinally, ask how they verify title documents for the properties they list. An agent who can clearly explain the verification process is far more likely to protect your interests throughout the transaction.",
            ],
            [
                'title' => 'What Rising Construction Costs Mean for Nigerian Homebuyers',
                'excerpt' => 'Higher material and labour costs are reshaping new-build pricing across the country.',
                'body' => "Construction costs have climbed steadily over the past year, driven by cement, steel, and imported fittings priced largely in foreign currency.\n\nFor buyers, this means newly completed developments are launching at higher price points than comparable projects delivered even eighteen months ago. Off-plan purchases, bought early in a development's construction cycle, remain one of the more effective ways to lock in pricing before costs are fully passed through.\n\nDevelopers are also increasingly turning to locally sourced materials and alternative building methods to manage costs, a trend likely to continue shaping the market through the rest of the year.",
            ],
        ];

        foreach ($posts as $post) {
            Post::firstOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'is_active' => true,
                ]
            );
        }
    }
}
