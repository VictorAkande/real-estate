<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\GeneratesUniqueSlugs;
use App\Http\Controllers\Controller;
use App\Models\AreaGuide;
use App\Support\ImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAreaGuideController extends Controller
{
    use GeneratesUniqueSlugs;

    public function index(): View
    {
        $areaGuides = AreaGuide::latest()->paginate(12);

        return view('admin.area-guides.index', compact('areaGuides'));
    }

    public function create(): View
    {
        return view('admin.area-guides.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'cover_image_file' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $data['slug'] = $this->uniqueSlug(AreaGuide::class, $data['name']);

        if ($request->hasFile('cover_image_file')) {
            try {
                $uploader = new ImageUploader();
                $upload = $uploader->upload($request->file('cover_image_file'), 'area-guides', 900, 600);
                $data['cover_image'] = $upload['path'];
                $data['cover_thumb'] = $upload['thumb'];
            } catch (\Throwable $e) {
                return back()->withInput()->withErrors(['cover_image_file' => 'Image upload failed: '.$e->getMessage()]);
            }
        }

        AreaGuide::create($data);

        return redirect()->route('admin.area-guides.index')->with('status', 'Area guide created.');
    }

    public function edit(AreaGuide $area_guide): View
    {
        return view('admin.area-guides.edit', ['guide' => $area_guide]);
    }

    public function update(Request $request, AreaGuide $area_guide): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'cover_image_file' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        if ($area_guide->name !== $data['name']) {
            $data['slug'] = $this->uniqueSlug(AreaGuide::class, $data['name'], 'slug', $area_guide->id);
        }

        if ($request->hasFile('cover_image_file')) {
            try {
                $uploader = new ImageUploader();
                $upload = $uploader->upload($request->file('cover_image_file'), 'area-guides', 900, 600);
            } catch (\Throwable $e) {
                return back()->withInput()->withErrors(['cover_image_file' => 'Image upload failed: '.$e->getMessage()]);
            }

            $uploader->delete($area_guide->cover_image, $area_guide->cover_thumb);
            $data['cover_image'] = $upload['path'];
            $data['cover_thumb'] = $upload['thumb'];
        }

        $area_guide->update($data);

        return redirect()->route('admin.area-guides.index')->with('status', 'Area guide updated.');
    }

    public function destroy(AreaGuide $area_guide): RedirectResponse
    {
        if ($area_guide->cover_image || $area_guide->cover_thumb) {
            (new ImageUploader())->delete($area_guide->cover_image, $area_guide->cover_thumb);
        }
        $area_guide->delete();

        return redirect()->route('admin.area-guides.index')->with('status', 'Area guide deleted.');
    }
}
