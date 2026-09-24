<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected function getFarmer()
    {
        $farmer = Farmer::where('user_id', Auth::id())->first();
        if (!$farmer) {
            abort(403, 'Farmer profile not found.');
        }
        return $farmer;
    }

    public function edit()
    {
        $farmer = $this->getFarmer();
        $markets = Market::orderBy('name')->get();
        return view('farmer.profile', compact('farmer', 'markets'));
    }

    public function update(Request $request)
    {
        $farmer = $this->getFarmer();

        $validated = $request->validate([
            'stall_name' => 'required|string|max:100',
            'contact_person' => 'required|string|max:100',
            'contact_number' => 'required|string|max:20',
            'market_id' => 'nullable|exists:markets,id',
            'address' => 'required|string|max:255',
            'operating_days' => 'required|string|max:100',
            'pickup_time_windows' => 'required|string|max:255',
            'cutoff_hours' => 'required|integer|min:0|max:48',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'bio' => 'nullable|string|max:1000',
            'image_url' => 'nullable|url|max:500',
        ]);

        $farmer->update($validated);

        // Also update contact info in user account
        $farmer->user->update([
            'name' => $validated['contact_person'],
            'contact_number' => $validated['contact_number'],
            'address' => $validated['address'],
        ]);

        return back()->with('success', 'Stall profile and market pickup details updated successfully!');
    }
}
