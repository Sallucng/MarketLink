<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $orderId)
    {
        $order = Order::where('customer_id', Auth::id())->findOrFail($orderId);

        if ($order->order_status !== 'completed') {
            return back()->with('error', 'Reviews can only be submitted after an order has been completed at pickup.');
        }

        if ($order->review) {
            return back()->with('error', 'You have already reviewed this order.');
        }

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
            'product_id' => 'nullable|exists:products,id',
        ]);

        Review::create([
            'order_id' => $order->id,
            'customer_id' => Auth::id(),
            'farmer_id' => $order->farmer_id,
            'product_id' => $data['product_id'] ?? null,
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);

        return back()->with('success', 'Thank you! Your feedback has been posted.');
    }
}
