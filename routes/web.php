<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingEnquiryController;
use App\Http\Controllers\Admin\AdminAgentController;
use App\Http\Controllers\Admin\AdminContentController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminListingController;
use App\Http\Controllers\Admin\AdminLocationController;
use App\Models\Agent;
use App\Models\ContentPage;
use App\Models\Listing;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredListings = Listing::with('location')
        ->where('featured', true)
        ->latest()
        ->take(4)
        ->get();

    $latestListings = Listing::with('location')
        ->latest()
        ->take(8)
        ->get();

    $featuredAgents = Agent::where('status', 'active')
        ->latest()
        ->take(3)
        ->get();

    $contentBlocks = ContentPage::whereIn('key', [
        'home_hero',
        'home_featured',
        'home_latest',
        'home_market',
        'home_list_property',
    ])->where('is_active', true)->get()->keyBy('key');

    $locations = Location::orderBy('name')->get();

    return view('site.home', compact('featuredListings', 'latestListings', 'featuredAgents', 'contentBlocks', 'locations'));
})->name('home');

Route::get('/for-sale', function (Request $request) {
    return view('site.listings', [
        'title' => 'Properties for Sale',
        'tagline' => 'Browse premium listings for sale across Nigeria.',
        'listings' => listingSearch($request, 'sale'),
        'locations' => Location::orderBy('name')->get(),
        'type' => 'sale',
    ]);
})->name('sale');

Route::get('/for-rent', function (Request $request) {
    return view('site.listings', [
        'title' => 'Properties for Rent',
        'tagline' => 'Find verified rentals in top neighborhoods.',
        'listings' => listingSearch($request, 'rent'),
        'locations' => Location::orderBy('name')->get(),
        'type' => 'rent',
    ]);
})->name('rent');

Route::get('/short-let', function (Request $request) {
    return view('site.listings', [
        'title' => 'Short Let Stays',
        'tagline' => 'Furnished short stays for business and leisure.',
        'listings' => listingSearch($request, 'shortlet'),
        'locations' => Location::orderBy('name')->get(),
        'type' => 'shortlet',
    ]);
})->name('shortlet');

Route::get('/listing/{listing:slug}', function (Listing $listing) {
    $listing->load(['location', 'agent', 'images']);

    return view('site.listing-detail', compact('listing'));
})->name('listing.detail');

Route::post('/listing/{listing:slug}/enquiry', [ListingEnquiryController::class, 'store'])->name('listing.enquiry');
Route::get('/listing/{listing:slug}/enquiry/thank-you', function (Listing $listing) {
    return view('site.listing-enquiry-thank-you', compact('listing'));
})->name('listing.enquiry.thankyou');

Route::get('/agents', function () {
    return view('site.companies', [
        'title' => 'Estate Agents',
        'tagline' => 'Work with trusted estate professionals.',
        'companies' => Agent::where('is_developer', false)->latest()->paginate(12),
    ]);
})->name('agents');

Route::get('/developers', function () {
    return view('site.companies', [
        'title' => 'Property Developers',
        'tagline' => 'Explore leading developers and their projects.',
        'companies' => Agent::where('is_developer', true)->latest()->paginate(12),
    ]);
})->name('developers');

Route::get('/market-trends', function () {
    return view('site.market');
})->name('market');

Route::get('/blog', function () {
    return view('site.blog');
})->name('blog');

Route::get('/area-guides', function () {
    return view('site.area-guides');
})->name('areas');

Route::get('/contact', function () {
    return view('site.contact');
})->name('contact');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('listings', AdminListingController::class)->names('listings');
    Route::post('listings/{listing}/gallery', [AdminListingController::class, 'uploadGallery'])->name('listings.gallery.upload');
    Route::post('listings/{listing}/gallery/order', [AdminListingController::class, 'reorderGallery'])->name('listings.gallery.order');
    Route::delete('listings/{listing}/gallery/{image}', [AdminListingController::class, 'deleteGalleryImage'])->name('listings.gallery.delete');
    Route::resource('agents', AdminAgentController::class)->names('agents');
    Route::resource('locations', AdminLocationController::class)->names('locations');
    Route::resource('content', AdminContentController::class)->parameters(['content' => 'content_page'])->names('content');
});

function listingSearch(Request $request, string $type)
{
    $query = Listing::with(['location', 'agent'])
        ->where('listing_type', $type)
        ->where('status', 'active');

    if ($request->filled('location_id')) {
        $query->where('location_id', $request->input('location_id'));
    }

    if ($request->filled('property_type')) {
        $query->where('property_type', $request->input('property_type'));
    }

    if ($request->filled('bedrooms')) {
        $query->where('bedrooms', '>=', $request->input('bedrooms'));
    }

    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->input('min_price'));
    }

    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->input('max_price'));
    }

    if ($request->filled('q')) {
        $search = $request->input('q');
        $query->where(function ($inner) use ($search) {
            $inner->where('title', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    switch ($request->input('sort')) {
        case 'price_low':
            $query->orderBy('price');
            break;
        case 'price_high':
            $query->orderByDesc('price');
            break;
        default:
            $query->latest();
            break;
    }

    return $query->paginate(12)->withQueryString();
}

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
