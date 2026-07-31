<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ListingEnquiryController;
use App\Http\Controllers\TwoFactorAuthenticationController;
use App\Http\Controllers\Admin\AdminAgentController;
use App\Http\Controllers\Admin\AdminAreaGuideController;
use App\Http\Controllers\Admin\AdminContentController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminListingController;
use App\Http\Controllers\Admin\AdminLocationController;
use App\Http\Controllers\Admin\AdminMarketTrendController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Models\Agent;
use App\Models\AreaGuide;
use App\Models\ContentPage;
use App\Models\Listing;
use App\Models\Location;
use App\Models\MarketTrend;
use App\Models\Post;
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

Route::get('/land', function (Request $request) {
    return view('site.listings', [
        'title' => 'Land for Sale',
        'tagline' => 'Browse verified land and plots across Nigeria.',
        'listings' => listingSearch($request, 'land'),
        'locations' => Location::orderBy('name')->get(),
        'type' => 'land',
    ]);
})->name('land');

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
    return view('site.market', [
        'trends' => MarketTrend::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
    ]);
})->name('market');

Route::get('/blog', function () {
    return view('site.blog', [
        'posts' => Post::where('is_active', true)->latest()->paginate(9),
    ]);
})->name('blog');

Route::get('/blog/{post:slug}', function (Post $post) {
    return view('site.blog-show', compact('post'));
})->name('blog.show');

Route::get('/area-guides', function () {
    return view('site.area-guides', [
        'areaGuides' => AreaGuide::where('is_active', true)->orderBy('name')->get(),
    ]);
})->name('areas');

Route::get('/area-guides/{areaGuide:slug}', function (AreaGuide $areaGuide) {
    return view('site.area-guide-show', compact('areaGuide'));
})->name('areas.show');

Route::get('/contact', function () {
    return view('site.contact');
})->name('contact');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.send');

Route::get('/contact/thank-you', function () {
    return view('site.contact-thank-you');
})->name('contact.thankyou');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin', 'two_factor'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('listings', AdminListingController::class)->names('listings');
    Route::post('listings/gallery/ajax-upload', [AdminListingController::class, 'uploadGalleryImageAjax'])->name('listings.gallery.ajax-upload');
    Route::post('listings/{listing}/gallery', [AdminListingController::class, 'uploadGallery'])->name('listings.gallery.upload');
    Route::post('listings/{listing}/gallery/order', [AdminListingController::class, 'reorderGallery'])->name('listings.gallery.order');
    Route::delete('listings/{listing}/gallery/{image}', [AdminListingController::class, 'deleteGalleryImage'])->name('listings.gallery.delete');
    Route::resource('agents', AdminAgentController::class)->names('agents');
    Route::resource('locations', AdminLocationController::class)->names('locations');
    Route::resource('content', AdminContentController::class)->parameters(['content' => 'content_page'])->names('content');
    Route::resource('market-trends', AdminMarketTrendController::class)->parameters(['market-trends' => 'market_trend'])->names('market-trends');
    Route::resource('posts', AdminPostController::class)->names('posts');
    Route::resource('area-guides', AdminAreaGuideController::class)->parameters(['area-guides' => 'area_guide'])->names('area-guides');
});

if (! function_exists('listingSearch')) {
function listingSearch(Request $request, string $type)
{
    $query = Listing::with(['location', 'agent', 'images'])
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
}

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])->name('two-factor.enable');
    Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])->name('two-factor.disable');
    Route::get('/user/two-factor-qr-code', [TwoFactorAuthenticationController::class, 'qrCode'])->name('two-factor.qr-code');
    Route::post('/user/confirmed-two-factor-authentication', [TwoFactorAuthenticationController::class, 'confirm'])->name('two-factor.confirm');
});

require __DIR__.'/auth.php';
