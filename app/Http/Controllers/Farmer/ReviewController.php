<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    protected function getFarmer()
    {
        $farmer = Farmer::where('user_id', Auth::id())->first();
        if (!$farmer) {
            abort(403, 'Farmer profile not found.');
        }
        return $farmer;
    }

    public function index()
    {
        $farmer = $this->getFarmer();
        $reviews = Review::where('farmer_id', $farmer->id)
            ->with(['customer', 'order', 'product'])
            ->latest()
            ->paginate(10);

        $avgRating = Review::where('farmer_id', $farmer->id)->avg('rating') ?: 0;
        $totalReviews = Review::where('farmer_id', $farmer->id)->count();

        return view('farmer.reviews.index', compact('farmer', 'reviews', 'avgRating', 'totalReviews'));
    }

    public function respond(Request $request, $id)
    {
        $farmer = $this->getFarmer();
        $review = Review::where('farmer_id', $farmer->id)->findOrFail($id);

        $validated = $request->validate([
            'farmer_response' => 'required|string|max:1000',
        ]);

        $review->update([
            'farmer_response' => $validated['farmer_response'],
        ]);

        return back()->with('success', 'Your response to the customer feedback has been posted.');
    }
}
