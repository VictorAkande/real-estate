<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Listing;
use App\Models\Location;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'listings' => Listing::count(),
            'agents' => Agent::count(),
            'locations' => Location::count(),
            'featured' => Listing::where('featured', true)->count(),
        ];

        $recentListings = Listing::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentListings'));
    }
}
