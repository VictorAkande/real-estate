<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\GeneratesUniqueSlugs;
use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLocationController extends Controller
{
    use GeneratesUniqueSlugs;

    public function index(): View
    {
        $locations = Location::latest()->paginate(12);

        return view('admin.locations.index', compact('locations'));
    }

    public function create(): View
    {
        return view('admin.locations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $data['slug'] = $this->uniqueSlug(Location::class, $data['name']);

        Location::create($data);

        return redirect()->route('admin.locations.index')->with('status', 'Location created.');
    }

    public function edit(Location $location): View
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        if ($location->name !== $data['name']) {
            $data['slug'] = $this->uniqueSlug(Location::class, $data['name'], 'slug', $location->id);
        }

        $location->update($data);

        return redirect()->route('admin.locations.index')->with('status', 'Location updated.');
    }

    public function destroy(Location $location): RedirectResponse
    {
        $location->delete();

        return redirect()->route('admin.locations.index')->with('status', 'Location deleted.');
    }
}
