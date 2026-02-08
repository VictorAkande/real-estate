<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Support\ImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAgentController extends Controller
{
    public function index(): View
    {
        $agents = Agent::latest()->paginate(12);

        return view('admin.agents.index', compact('agents'));
    }

    public function create(): View
    {
        return view('admin.agents.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'is_developer' => ['nullable', 'boolean'],
            'website' => ['nullable', 'string', 'max:255'],
            'logo_url' => ['nullable', 'string', 'max:255'],
            'logo_file' => ['nullable', 'image', 'max:2048'],
            'address' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        $data['is_developer'] = (bool) ($data['is_developer'] ?? false);

        if ($request->hasFile('logo_file')) {
            $uploader = new ImageUploader();
            $upload = $uploader->upload($request->file('logo_file'), 'agents', 240, 240);
            $data['logo_url'] = $upload['path'];
            $data['logo_thumb'] = $upload['thumb'];
        }

        Agent::create($data);

        return redirect()->route('admin.agents.index')->with('status', 'Agent created.');
    }

    public function edit(Agent $agent): View
    {
        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'is_developer' => ['nullable', 'boolean'],
            'website' => ['nullable', 'string', 'max:255'],
            'logo_url' => ['nullable', 'string', 'max:255'],
            'logo_file' => ['nullable', 'image', 'max:2048'],
            'address' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        $data['is_developer'] = (bool) ($data['is_developer'] ?? false);

        if ($request->hasFile('logo_file')) {
            $uploader = new ImageUploader();
            $uploader->delete($agent->logo_url, $agent->logo_thumb);

            $upload = $uploader->upload($request->file('logo_file'), 'agents', 240, 240);
            $data['logo_url'] = $upload['path'];
            $data['logo_thumb'] = $upload['thumb'];
        }

        $agent->update($data);

        return redirect()->route('admin.agents.index')->with('status', 'Agent updated.');
    }

    public function destroy(Agent $agent): RedirectResponse
    {
        if ($agent->logo_url || $agent->logo_thumb) {
            (new ImageUploader())->delete($agent->logo_url, $agent->logo_thumb);
        }
        $agent->delete();

        return redirect()->route('admin.agents.index')->with('status', 'Agent deleted.');
    }
}
