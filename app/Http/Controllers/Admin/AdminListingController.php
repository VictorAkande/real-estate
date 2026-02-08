<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\Location;
use App\Support\ImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminListingController extends Controller
{
    public function index(): View
    {
        $listings = Listing::with(['location', 'agent'])
            ->latest()
            ->paginate(12);

        return view('admin.listings.index', compact('listings'));
    }

    public function create(): View
    {
        $locations = Location::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();

        return view('admin.listings.create', compact('locations', 'agents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'listing_type' => ['required', 'in:sale,rent,shortlet'],
            'property_type' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'toilets' => ['nullable', 'integer', 'min:0'],
            'parking_spaces' => ['nullable', 'integer', 'min:0'],
            'area_sqm' => ['nullable', 'numeric', 'min:0'],
            'address' => ['required', 'string', 'max:255'],
            'location_id' => ['required', 'exists:locations,id'],
            'agent_id' => ['nullable', 'exists:agents,id'],
            'status' => ['required', 'string', 'max:50'],
            'featured' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'cover_image_file' => ['nullable', 'image', 'max:4096'],
            'description' => ['nullable', 'string'],
        ]);

        $data['featured'] = (bool) ($data['featured'] ?? false);
        $data['slug'] = $this->uniqueSlug($data['title']);

        if ($request->hasFile('cover_image_file')) {
            $uploader = new ImageUploader();
            $upload = $uploader->upload($request->file('cover_image_file'), 'listings', 640, 420);
            $data['cover_image'] = $upload['path'];
            $data['cover_thumb'] = $upload['thumb'];
        }

        Listing::create($data);

        return redirect()->route('admin.listings.index')->with('status', 'Listing created.');
    }

    public function edit(Listing $listing): View
    {
        $locations = Location::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();
        $gallery = $listing->images()->get();

        return view('admin.listings.edit', compact('listing', 'locations', 'agents', 'gallery'));
    }

    public function update(Request $request, Listing $listing): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'listing_type' => ['required', 'in:sale,rent,shortlet'],
            'property_type' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'toilets' => ['nullable', 'integer', 'min:0'],
            'parking_spaces' => ['nullable', 'integer', 'min:0'],
            'area_sqm' => ['nullable', 'numeric', 'min:0'],
            'address' => ['required', 'string', 'max:255'],
            'location_id' => ['required', 'exists:locations,id'],
            'agent_id' => ['nullable', 'exists:agents,id'],
            'status' => ['required', 'string', 'max:50'],
            'featured' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'cover_image_file' => ['nullable', 'image', 'max:4096'],
            'description' => ['nullable', 'string'],
        ]);

        $data['featured'] = (bool) ($data['featured'] ?? false);

        if ($listing->title !== $data['title']) {
            $data['slug'] = $this->uniqueSlug($data['title'], $listing->id);
        }

        if ($request->hasFile('cover_image_file')) {
            $uploader = new ImageUploader();
            $uploader->delete($listing->cover_image, $listing->cover_thumb);

            $upload = $uploader->upload($request->file('cover_image_file'), 'listings', 640, 420);
            $data['cover_image'] = $upload['path'];
            $data['cover_thumb'] = $upload['thumb'];
        }

        $listing->update($data);

        return redirect()->route('admin.listings.index')->with('status', 'Listing updated.');
    }

    public function destroy(Listing $listing): RedirectResponse
    {
        if ($listing->cover_image || $listing->cover_thumb) {
            (new ImageUploader())->delete($listing->cover_image, $listing->cover_thumb);
        }
        foreach ($listing->images as $image) {
            (new ImageUploader())->delete($image->image_path, $image->thumb_path);
        }
        $listing->delete();

        return redirect()->route('admin.listings.index')->with('status', 'Listing deleted.');
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 1;

        while (Listing::where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    public function uploadGallery(Request $request, Listing $listing): RedirectResponse
    {
        $data = $request->validate([
            'gallery_images' => ['required'],
            'gallery_images.*' => ['image', 'max:4096'],
        ]);

        $orderStart = (int) $listing->images()->max('sort_order');
        $uploader = new ImageUploader();

        foreach ($request->file('gallery_images', []) as $file) {
            $orderStart++;
            $upload = $uploader->upload($file, 'listings/gallery', 900, 600);

            ListingImage::create([
                'listing_id' => $listing->id,
                'image_path' => $upload['path'],
                'thumb_path' => $upload['thumb'],
                'sort_order' => $orderStart,
            ]);
        }

        return redirect()->route('admin.listings.edit', $listing)->with('status', 'Gallery images uploaded.');
    }

    public function reorderGallery(Request $request, Listing $listing): RedirectResponse
    {
        $data = $request->validate([
            'order' => ['required', 'string'],
        ]);

        $ids = array_filter(array_map('intval', explode(',', $data['order'])));

        foreach ($ids as $index => $imageId) {
            ListingImage::where('listing_id', $listing->id)
                ->where('id', $imageId)
                ->update(['sort_order' => $index + 1]);
        }

        return redirect()->route('admin.listings.edit', $listing)->with('status', 'Gallery order updated.');
    }

    public function deleteGalleryImage(Listing $listing, ListingImage $image): RedirectResponse
    {
        if ($image->listing_id !== $listing->id) {
            abort(404);
        }

        (new ImageUploader())->delete($image->image_path, $image->thumb_path);
        $image->delete();

        return redirect()->route('admin.listings.edit', $listing)->with('status', 'Gallery image deleted.');
    }
}
