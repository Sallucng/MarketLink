<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\Farmer;
use App\Models\Market;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('is_active', true)->latest()->take(3)->get();
        $categories = Category::withCount('products')->get();
        $markets = Market::withCount(['farmers' => function ($q) {
            $q->whereHas('user', function ($uq) {
                $uq->where('is_approved', true);
            });
        }])->get();

        $featuredProducts = Product::where('is_available', true)
            ->where('is_sold_out', false)
            ->whereHas('farmer.user', function ($q) {
                $q->where('is_approved', true)->where('is_active', true);
            })
            ->with(['farmer', 'category'])
            ->latest()
            ->take(8)
            ->get();

        $stats = [
            'farmers' => Farmer::whereHas('user', fn($q) => $q->where('is_approved', true))->count(),
            'markets' => Market::count(),
            'products' => Product::where('is_available', true)->count(),
        ];

        return view('public.home', compact('announcements', 'categories', 'markets', 'featuredProducts', 'stats'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function contact()
    {
        return view('public.contact');
    }
}
