<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Market;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'total_farmers' => Farmer::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_markets' => Market::count(),
            'total_orders' => Order::count(),
            'total_volume' => Order::sum('total_amount'),
        ];

        $pendingFarmers = Farmer::whereHas('user', function ($q) {
            $q->where('is_approved', false);
        })->with('user', 'market')->get();

        $activeFarmers = Farmer::whereHas('user', function ($q) {
            $q->where('is_approved', true);
        })->with('user', 'market')->get();

        $customers = User::where('role', 'customer')->latest()->take(10)->get();
        $recentOrders = Order::with('customer', 'farmer')->latest()->take(8)->get();

        return view('admin.dashboard', compact('metrics', 'pendingFarmers', 'activeFarmers', 'customers', 'recentOrders'));
    }

    public function approveFarmer($id)
    {
        $farmer = Farmer::with('user')->findOrFail($id);
        $farmer->user->is_approved = true;
        $farmer->user->save();

        return back()->with('success', "Stall '{$farmer->stall_name}' has been approved and can now list products.");
    }

    public function suspendFarmer($id)
    {
        $farmer = Farmer::with('user')->findOrFail($id);
        $farmer->user->is_approved = false;
        $farmer->user->save();

        return back()->with('warning', "Stall '{$farmer->stall_name}' has been suspended.");
    }

    public function toggleCustomerStatus($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        $customer->is_active = !$customer->is_active;
        $customer->save();

        $status = $customer->is_active ? 'activated' : 'deactivated';
        return back()->with('info', "Customer '{$customer->name}' has been {$status}.");
    }
}
