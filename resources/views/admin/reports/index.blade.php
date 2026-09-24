@extends('layouts.app')

@section('title', 'Platform Reports & Analytics — Admin MarketLink')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-success">Admin Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Reports & Analytics</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <span class="badge bg-primary text-white px-3 py-1 rounded-pill mb-1">System Intelligence</span>
            <h2 class="heading-serif fw-bold text-dark mb-0">Platform Reports & Analytics</h2>
            <small class="text-muted">Multi-market sales performance, order pipelines, and top active growers (SRS §1.6)</small>
        </div>
    </div>

    <!-- Overview Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-4">
            <div class="card card-custom p-4 bg-white border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Pre-Orders Placed</div>
                        <h2 class="display-6 fw-bold text-dark mb-0">{{ $totalOrders }}</h2>
                    </div>
                    <div class="p-3 bg-brand-light text-success rounded-circle">
                        <i class="bi bi-receipt-cutoff fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card card-custom p-4 bg-white border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Completed Collections</div>
                        <h2 class="display-6 fw-bold text-primary mb-0">{{ $completedOrders }}</h2>
                    </div>
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle">
                        <i class="bi bi-bag-check fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card card-custom p-4 bg-white border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total In-Person Sales Volume</div>
                        <h2 class="display-6 fw-bold text-success mb-0">${{ number_format($totalRevenue, 2) }}</h2>
                    </div>
                    <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                        <i class="bi bi-cash-stack fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Market Performance Table (SRS §1.6) -->
        <div class="col-lg-8">
            <div class="card card-custom p-4 bg-white border-0 shadow-sm h-100">
                <h5 class="heading-serif fw-bold text-dark mb-3">Revenue & Orders by Farmers Market Venue</h5>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th>Market Plaza</th>
                                <th>City</th>
                                <th class="text-center">Attending Stalls</th>
                                <th class="text-center">Total Orders</th>
                                <th class="text-end">Settled Volume</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($marketStats as $m)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $m->name }}</div>
                                        <small class="text-muted">{{ $m->address }}</small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ $m->city }}</span></td>
                                    <td class="text-center fw-semibold">{{ $m->farmers_count }}</td>
                                    <td class="text-center">{{ $m->orders_count }}</td>
                                    <td class="text-end fw-bold text-success fs-6">${{ number_format($m->revenue, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Status Pipeline Breakdown -->
        <div class="col-lg-4">
            <div class="card card-custom p-4 bg-white border-0 shadow-sm h-100">
                <h5 class="heading-serif fw-bold text-dark mb-3">Pipeline Breakdown</h5>
                <ul class="list-group list-group-flush">
                    @php
                        $statusLabels = [
                            'placed' => ['name' => 'Newly Placed', 'class' => 'bg-warning text-dark'],
                            'accepted' => ['name' => 'Accepted by Farmer', 'class' => 'bg-primary'],
                            'ready_for_pickup' => ['name' => 'Packed & Ready', 'class' => 'bg-info text-dark'],
                            'completed' => ['name' => 'Completed & Settled', 'class' => 'bg-success'],
                            'cancelled' => ['name' => 'Customer Cancelled', 'class' => 'bg-secondary'],
                            'declined' => ['name' => 'Farmer Declined', 'class' => 'bg-danger'],
                        ];
                    @endphp
                    @foreach($statusLabels as $key => $meta)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="small fw-semibold text-dark">{{ $meta['name'] }}</span>
                            <span class="badge {{ $meta['class'] }} rounded-pill">{{ $statusBreakdown[$key] ?? 0 }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Most Active Farmers (SRS §1.6) -->
    <div class="card card-custom p-4 bg-white border-0 shadow-sm">
        <h5 class="heading-serif fw-bold text-dark mb-3">Most Active Farmers & Vendors</h5>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th>Farmer Stall</th>
                        <th>Contact Grower</th>
                        <th>Home Market</th>
                        <th class="text-center">Total Orders</th>
                        <th class="text-end">Completed Sales</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topFarmers as $tf)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="p-2 bg-brand-light text-success rounded-circle">
                                        <i class="bi bi-shop"></i>
                                    </div>
                                    <div class="fw-bold text-dark">{{ $tf->stall_name }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">{{ $tf->contact_person }}</div>
                                <small class="text-muted">{{ $tf->contact_number }}</small>
                            </td>
                            <td><span class="small text-muted">{{ $tf->market->name ?? 'Unassigned' }}</span></td>
                            <td class="text-center fw-bold">{{ $tf->orders_count }}</td>
                            <td class="text-end fw-bold text-success fs-6">${{ number_format($tf->total_sales, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
