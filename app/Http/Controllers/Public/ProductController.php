<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Market;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $markets = Market::all();

        $query = Product::where('is_available', true)
            ->whereHas('farmer.user', function ($q) {
                $q->where('is_approved', true)->where('is_active', true);
            })
            ->with(['farmer.market', 'category']);

        // Search Keyword
        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%");
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Market Filter
        if ($request->filled('market')) {
            $query->whereHas('farmer', function ($q) use ($request) {
                $q->where('market_id', $request->input('market'));
            });
        }

        // Day Filter
        if ($request->filled('day')) {
            $day = $request->input('day');
            $query->whereHas('farmer.market', function ($q) use ($day) {
                $q->where('operating_days', 'LIKE', "%{$day}%");
            });
        }

        // Max Price Filter
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Sort
        switch ($request->input('sort')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        return view('public.products', compact('products', 'categories', 'markets'));
    }

    public function show($id)
    {
        $product = Product::whereHas('farmer.user', function ($q) {
            $q->where('is_approved', true)->where('is_active', true);
        })->with(['farmer.market', 'category', 'reviews.customer'])->findOrFail($id);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->whereHas('farmer.user', fn($q) => $q->where('is_approved', true))
            ->take(4)
            ->get();

        return view('public.product-detail', compact('product', 'relatedProducts'));
    }
}
