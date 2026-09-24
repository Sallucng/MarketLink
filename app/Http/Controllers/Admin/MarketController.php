<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Market;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index()
    {
        $markets = Market::withCount('farmers')->latest()->paginate(10);
        return view('admin.markets.index', compact('markets'));
    }

    public function create()
    {
        return view('admin.markets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'operating_days' => 'required|string|max:100',
            'timings' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:500',
        ]);

        $validated['map_provider'] = 'OpenStreetMap';
        if (empty($validated['image_url'])) {
            $validated['image_url'] = 'https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=800&q=80';
        }

        Market::create($validated);

        return redirect()->route('admin.markets.index')->with('success', 'Farmers market added successfully!');
    }

    public function edit($id)
    {
        $market = Market::findOrFail($id);
        return view('admin.markets.edit', compact('market'));
    }

    public function update(Request $request, $id)
    {
        $market = Market::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'operating_days' => 'required|string|max:100',
            'timings' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:500',
        ]);

        $market->update($validated);

        return redirect()->route('admin.markets.index')->with('success', 'Market details updated successfully!');
    }

    public function destroy($id)
    {
        $market = Market::findOrFail($id);
        $market->delete();

        return back()->with('success', 'Market removed from the system.');
    }
}
