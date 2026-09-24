@extends('layouts.app')

@section('title', 'Farmer Vendor Dashboard — MarketLink')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Farmer Dashboard</li>
        </ol>
    </nav>

    @php
        $user = Auth::user();
        $farmer = $user->farmerProfile ?? \App\Models\Farmer::first();
        $orders = $farmer ? $farmer->orders()->with('customer', 'items.product')->latest()->take(5)->get() : collect();
        $totalOrders = $farmer ? $farmer->orders()->count() : 0;
        $pendingOrders = $farmer ? $farmer->orders()->whereIn('order_status', ['placed', 'accepted'])->count() : 0;
        $revenue = $farmer ? $farmer->orders()->where('order_status', 'completed')->sum('total_amount') : 0;
    @endphp

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <span class="badge badge-brand px-3 py-1 rounded-pill mb-1">Farmer / Vendor Portal</span>
            <h2 class="heading-serif fw-bold text-dark mb-0">{{ $farmer ? $farmer->stall_name : 'Farmer Management Portal' }}</h2>
            <small class="text-muted">Managed by {{ $user->name }} &bull; Market: {{ $farmer->market->name ?? 'Downtown Farmers Plaza' }}</small>
        </div>

        @if(!$user->is_approved)
            <div class="alert alert-warning py-2 px-3 mb-0 small">
                <i class="bi bi-clock-history me-1"></i> <strong>Pending Approval:</strong> Your stall profile is awaiting administrator approval before listings go live.
            </div>
        @else
            <span class="badge bg-success px-3 py-2 text-uppercase">
                <i class="bi bi-check-circle-fill me-1"></i> Active Verified Stall
            </span>
        @endif
    </div>

    <!-- Sales & Pre-Order Insights (SRS §1.6: Total Orders, Pending Orders, Revenue Summary) -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-4">
            <div class="card card-custom p-4 bg-white border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Pre-Orders</div>
                        <h2 class="display-6 fw-bold text-dark mb-0">{{ $totalOrders }}</h2>
                    </div>
                    <div class="p-3 bg-brand-light text-success rounded-circle">
                        <i class="bi bi-box-seam fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card card-custom p-4 bg-white border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Pending Pickup</div>
                        <h2 class="display-6 fw-bold text-warning mb-0">{{ $pendingOrders }}</h2>
                    </div>
                    <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-circle">
                        <i class="bi bi-hourglass-split fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card card-custom p-4 bg-white border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Revenue Settled</div>
                        <h2 class="display-6 fw-bold text-success mb-0">${{ number_format($revenue, 2) }}</h2>
                    </div>
                    <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                        <i class="bi bi-cash-coin fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incoming Pre-Orders Queue (SRS §1.6) -->
    <div class="card card-custom p-4 bg-white border-0 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="heading-serif fw-bold text-dark mb-0">Recent Pre-Orders Queue</h5>
            <span class="small text-muted">Showing latest 5 orders</span>
        </div>

        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Pickup Date & Slot</th>
                            <th>Items Reserved</th>
                            <th class="text-center">Total Due</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $o)
                            <tr>
                                <td class="fw-bold font-monospace">{{ $o->order_number }}</td>
                                <td>{{ $o->customer->name }} ({{ $o->customer->contact_number }})</td>
                                <td>
                                    <div>{{ $o->pickup_date->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $o->pickup_time_slot }}</small>
                                </td>
                                <td>{{ $o->items->sum('quantity') }} items</td>
                                <td class="text-center fw-bold">${{ number_format($o->total_amount, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary text-uppercase">{{ str_replace('_', ' ', $o->order_status) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted small mb-0">No pre-orders recorded for this stall yet.</p>
        @endif
    </div>

    <!-- Stall Configuration Details -->
    <div class="card card-custom p-4 bg-white border-0 shadow-sm">
        <h5 class="heading-serif fw-bold text-dark mb-3">Stall Pickup Configuration</h5>
        <div class="row g-3 small text-secondary">
            <div class="col-md-4">
                <strong>Stall Location:</strong>
                <div>{{ $farmer->address ?? 'Market Corridor' }}</div>
            </div>
            <div class="col-md-4">
                <strong>Available Pickup Windows:</strong>
                <div>{{ $farmer->pickup_time_windows ?? 'Market Hours' }}</div>
            </div>
            <div class="col-md-4">
                <strong>Pre-Order Cutoff:</strong>
                <div>{{ $farmer->cutoff_hours ?? 2 }} hours before pickup</div>
            </div>
        </div>
    </div>
</div>
@endsection
