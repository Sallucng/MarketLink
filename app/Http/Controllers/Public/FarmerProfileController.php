<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use Illuminate\Http\Request;

class FarmerProfileController extends Controller
{
    public function show($id)
    {
        $farmer = Farmer::whereHas('user', function ($q) {
            $q->where('is_approved', true)->where('is_active', true);
        })->with([
            'market',
            'products' => function ($q) {
                $q->where('is_available', true)->with('category');
            },
            'reviews.customer',
            'reviews.product',
        ])->findOrFail($id);

        return view('public.farmer-detail', compact('farmer'));
    }
}
