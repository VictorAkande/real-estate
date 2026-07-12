<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\GeneratesUniqueSlugs;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\ImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPostController extends Controller
{
    use GeneratesUniqueSlugs;

    public function index(): View
    {
        $posts = Post::latest()->paginate(12);

        return view('admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin.posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'cover_image_file' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $data['slug'] = $this->uniqueSlug(Post::class, $data['title']);

        if ($request->hasFile('cover_image_file')) {
            $uploader = new ImageUploader();
            $upload = $uploader->upload($request->file('cover_image_file'), 'posts', 900, 600);
            $data['cover_image'] = $upload['path'];
            $data['cover_thumb'] = $upload['thumb'];
        }

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('status', 'Post created.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'cover_image_file' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        if ($post->title !== $data['title']) {
            $data['slug'] = $this->uniqueSlug(Post::class, $data['title'], 'slug', $post->id);
        }

        if ($request->hasFile('cover_image_file')) {
            $uploader = new ImageUploader();
            $uploader->delete($post->cover_image, $post->cover_thumb);

            $upload = $uploader->upload($request->file('cover_image_file'), 'posts', 900, 600);
            $data['cover_image'] = $upload['path'];
            $data['cover_thumb'] = $upload['thumb'];
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('status', 'Post updated.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->cover_image || $post->cover_thumb) {
            (new ImageUploader())->delete($post->cover_image, $post->cover_thumb);
        }
        $post->delete();

        return redirect()->route('admin.posts.index')->with('status', 'Post deleted.');
    }
}
