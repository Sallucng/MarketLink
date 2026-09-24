<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class FarmerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $farmer = Farmer::where('user_id', $user->id)->with('market')->first();

        if (!$farmer) {
            $farmer = Farmer::create([
                'user_id' => $user->id,
                'stall_name' => $user->name . "'s Farm Stall",
                'contact_person' => $user->name,
                'contact_number' => $user->contact_number ?? '555-0100',
                'address' => $user->address ?? 'Market Stall Row A',
                'operating_days' => 'Saturday, Sunday',
                'pickup_time_windows' => '08:00 AM - 10:00 AM, 10:30 AM - 12:30 PM, 01:00 PM - 03:00 PM',
                'cutoff_hours' => 2,
            ]);
        }

        $totalOrders = Order::where('farmer_id', $farmer->id)->count();
        $pendingOrders = Order::where('farmer_id', $farmer->id)->whereIn('order_status', ['placed', 'accepted'])->count();
        $todayPickups = Order::where('farmer_id', $farmer->id)->whereDate('pickup_date', today())->count();
        $totalRevenue = Order::where('farmer_id', $farmer->id)->where('order_status', 'completed')->sum('total_amount');

        $recentOrders = Order::where('farmer_id', $farmer->id)
            ->with(['customer', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        $bestSellers = Product::where('farmer_id', $farmer->id)
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        return view('farmer.dashboard', compact(
            'farmer',
            'user',
            'totalOrders',
            'pendingOrders',
            'todayPickups',
            'totalRevenue',
            'recentOrders',
            'bestSellers'
        ));
    }
}
