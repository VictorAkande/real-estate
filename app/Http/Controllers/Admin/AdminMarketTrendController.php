<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketTrend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMarketTrendController extends Controller
{
    public function index(): View
    {
        $marketTrends = MarketTrend::orderBy('sort_order')->orderBy('id')->paginate(12);

        return view('admin.market-trends.index', compact('marketTrends'));
    }

    public function create(): View
    {
        return view('admin.market-trends.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'metric' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        MarketTrend::create($data);

        return redirect()->route('admin.market-trends.index')->with('status', 'Market trend created.');
    }

    public function edit(MarketTrend $market_trend): View
    {
        return view('admin.market-trends.edit', ['trend' => $market_trend]);
    }

    public function update(Request $request, MarketTrend $market_trend): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'metric' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $market_trend->update($data);

        return redirect()->route('admin.market-trends.index')->with('status', 'Market trend updated.');
    }

    public function destroy(MarketTrend $market_trend): RedirectResponse
    {
        $market_trend->delete();

        return redirect()->route('admin.market-trends.index')->with('status', 'Market trend deleted.');
    }
}
