<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentPage;
use App\Support\ImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminContentController extends Controller
{
    public function index(): View
    {
        $pages = ContentPage::latest()->paginate(12);

        return view('admin.content.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.content.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:255', 'unique:content_pages,key'],
            'template' => ['required', 'in:hero,feature,stats'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'cta_text' => ['nullable', 'string', 'max:255'],
            'cta_link' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        if ($request->hasFile('image_file')) {
            $uploader = new ImageUploader();
            $upload = $uploader->upload($request->file('image_file'), 'content', 1000, 700);
            $data['image_path'] = $upload['path'];
        }

        ContentPage::create($data);

        return redirect()->route('admin.content.index')->with('status', 'Content page created.');
    }

    public function edit(ContentPage $content_page): View
    {
        return view('admin.content.edit', ['page' => $content_page]);
    }

    public function update(Request $request, ContentPage $content_page): RedirectResponse
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:255', 'unique:content_pages,key,'.$content_page->id],
            'template' => ['required', 'in:hero,feature,stats'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'cta_text' => ['nullable', 'string', 'max:255'],
            'cta_link' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        if ($request->hasFile('image_file')) {
            $uploader = new ImageUploader();
            $uploader->delete($content_page->image_path, null);

            $upload = $uploader->upload($request->file('image_file'), 'content', 1000, 700);
            $data['image_path'] = $upload['path'];
        }

        $content_page->update($data);

        return redirect()->route('admin.content.index')->with('status', 'Content page updated.');
    }

    public function destroy(ContentPage $content_page): RedirectResponse
    {
        if ($content_page->image_path) {
            (new ImageUploader())->delete($content_page->image_path, null);
        }
        $content_page->delete();

        return redirect()->route('admin.content.index')->with('status', 'Content page deleted.');
    }
}
