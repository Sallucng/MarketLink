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

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <span class="badge badge-brand px-3 py-1 rounded-pill mb-1">Farmer / Vendor Portal</span>
            <h2 class="heading-serif fw-bold text-dark mb-0">{{ $farmer->stall_name }}</h2>
            <small class="text-muted">Managed by {{ $user->name }} &bull; Market: {{ $farmer->market->name ?? 'Unassigned Market' }}</small>
        </div>

        <div class="d-flex align-items-center gap-2">
            @if(!$user->is_approved)
                <div class="alert alert-warning py-2 px-3 mb-0 small border-warning">
                    <i class="bi bi-clock-history me-1"></i> <strong>Pending Admin Approval:</strong> Your stall profile is awaiting approval before public listing.
                </div>
            @else
                <span class="badge bg-success px-3 py-2 text-uppercase">
                    <i class="bi bi-check-circle-fill me-1"></i> Active Verified Stall
                </span>
            @endif

            <a href="{{ route('farmer.products.create') }}" class="btn btn-brand btn-sm rounded-pill px-3">
                <i class="bi bi-plus-circle me-1"></i> Add Produce
            </a>
        </div>
    </div>

    <!-- Quick Navigation Tabs -->
    <div class="card card-custom p-2 bg-white border-0 shadow-sm mb-4">
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
                <a class="nav-link active fw-semibold" href="{{ route('farmer.dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold text-secondary" href="{{ route('farmer.products.index') }}"><i class="bi bi-boxes me-1"></i> Weekly Inventory</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold text-secondary" href="{{ route('farmer.orders.index') }}"><i class="bi bi-receipt me-1"></i> Pre-Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold text-secondary" href="{{ route('farmer.profile') }}"><i class="bi bi-shop me-1"></i> Stall Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold text-secondary" href="{{ route('farmer.reviews.index') }}"><i class="bi bi-star me-1"></i> Customer Reviews</a>
            </li>
        </ul>
    </div>

    <!-- Sales & Pre-Order Insights (SRS §1.6: Total Orders, Pending Orders, Revenue Summary) -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-custom p-4 bg-white border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Orders</div>
                        <h2 class="display-6 fw-bold text-dark mb-0">{{ $totalOrders }}</h2>
                    </div>
                    <div class="p-3 bg-brand-light text-success rounded-circle">
                        <i class="bi bi-box-seam fs-3"></i>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">Lifetime stall reservations</small>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-custom p-4 bg-white border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Pending Orders</div>
                        <h2 class="display-6 fw-bold text-warning mb-0">{{ $pendingOrders }}</h2>
                    </div>
                    <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-circle">
                        <i class="bi bi-hourglass-split fs-3"></i>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">Awaiting packing or confirmation</small>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-custom p-4 bg-white border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Today's Pickups</div>
                        <h2 class="display-6 fw-bold text-primary mb-0">{{ $todayPickups }}</h2>
                    </div>
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle">
                        <i class="bi bi-calendar-event fs-3"></i>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">Scheduled for pickup today</small>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-custom p-4 bg-white border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Revenue Summary</div>
                        <h2 class="display-6 fw-bold text-success mb-0">${{ number_format($totalRevenue, 2) }}</h2>
                    </div>
                    <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                        <i class="bi bi-cash-coin fs-3"></i>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">Settled in person upon pickup</small>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Recent Incoming Orders -->
        <div class="col-lg-8">
            <div class="card card-custom p-4 bg-white border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="heading-serif fw-bold text-dark mb-0">Incoming Customer Pre-Orders</h5>
                    <a href="{{ route('farmer.orders.index') }}" class="small text-success fw-bold text-decoration-none">View All Pre-Orders &rarr;</a>
                </div>

                @if($recentOrders->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted display-4"></i>
                        <p class="text-muted mt-2">No incoming pre-orders yet. Ensure your weekly stock is published!</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Pickup Slot</th>
                                    <th>Total Due</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $ord)
                                    <tr>
                                        <td class="fw-bold">
                                            <a href="{{ route('farmer.orders.show', $ord->id) }}" class="text-success text-decoration-none">
                                                {{ $ord->order_number }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="fw-bold small">{{ $ord->customer->name ?? 'Guest' }}</div>
                                            <small class="text-muted">{{ $ord->customer->contact_number ?? '' }}</small>
                                        </td>
                                        <td class="small">
                                            <div>{{ $ord->pickup_date->format('M d, Y') }}</div>
                                            <span class="text-muted">{{ $ord->pickup_time_slot }}</span>
                                        </td>
                                        <td class="fw-bold text-success">${{ number_format($ord->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge 
                                                {{ $ord->order_status === 'completed' ? 'bg-success' : '' }}
                                                {{ $ord->order_status === 'ready_for_pickup' ? 'bg-info text-dark' : '' }}
                                                {{ $ord->order_status === 'accepted' ? 'bg-primary' : '' }}
                                                {{ $ord->order_status === 'placed' ? 'bg-warning text-dark' : '' }}
                                                {{ in_array($ord->order_status, ['cancelled', 'declined']) ? 'bg-danger' : '' }}
                                                rounded-pill text-uppercase" style="font-size: 0.72rem;">
                                                {{ str_replace('_', ' ', $ord->order_status) }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('farmer.orders.show', $ord->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                                Manage
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Best-Selling Products Sidebar -->
        <div class="col-lg-4">
            <div class="card card-custom p-4 bg-white border-0 shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="heading-serif fw-bold text-dark mb-0">Best-Selling Produce</h5>
                    <a href="{{ route('farmer.products.index') }}" class="small text-success text-decoration-none">Manage</a>
                </div>

                @if($bestSellers->isEmpty())
                    <p class="text-muted small mb-0">Best seller rankings will appear as customers place reservations.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($bestSellers as $prod)
                            <li class="list-group-item px-0 py-2 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="rounded" style="width: 42px; height: 42px; object-fit: cover;">
                                    <div>
                                        <div class="fw-bold small text-dark">{{ $prod->name }}</div>
                                        <small class="text-muted">${{ number_format($prod->price, 2) }} / {{ $prod->unit }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-light text-dark border rounded-pill">{{ $prod->order_items_count }} sold</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Weekly Stock Template Quick Action (SRS §1.6) -->
            <div class="card card-custom p-4 bg-brand-light border-0 shadow-sm">
                <h6 class="fw-bold text-success mb-2"><i class="bi bi-arrow-repeat me-1"></i> Quick Stock Replenish</h6>
                <p class="small text-muted mb-3">
                    Reset your harvest inventory to your predefined weekly template quantities with one click for market morning.
                </p>
                <form action="{{ route('farmer.products.replenish') }}" method="POST" onsubmit="return confirm('Apply weekly recurring stock template to all products?')">
                    @csrf
                    <button type="submit" class="btn btn-brand btn-sm w-100 rounded-pill">
                        Apply Weekly Template
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
