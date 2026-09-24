@extends('layouts.app')

@section('title', 'Admin Platform Dashboard — MarketLink')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Admin Dashboard</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="badge bg-primary text-white px-3 py-1 rounded-pill mb-1">Administrative Oversight</span>
            <h2 class="heading-serif fw-bold text-dark mb-0">Platform Overview & Management</h2>
        </div>
        <div class="text-muted small">
            Logged in as System Administrator
        </div>
    </div>

    <!-- Admin Navigation Toolbar -->
    <div class="card card-custom p-2 bg-white border-0 shadow-sm mb-4">
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
                <a class="nav-link active fw-semibold" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold text-secondary" href="{{ route('admin.markets.index') }}"><i class="bi bi-geo-alt me-1"></i> Manage Markets</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold text-secondary" href="{{ route('admin.categories.index') }}"><i class="bi bi-tags me-1"></i> Categories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold text-secondary" href="{{ route('admin.moderation.reviews') }}"><i class="bi bi-shield-check me-1"></i> Moderation</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold text-secondary" href="{{ route('admin.reports.index') }}"><i class="bi bi-graph-up me-1"></i> Reports</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold text-secondary" href="{{ route('admin.announcements.index') }}"><i class="bi bi-megaphone me-1"></i> Announcements</a>
            </li>
        </ul>
    </div>

    <!-- Platform Key Metrics (SRS §1.6) -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg">
            <div class="card card-custom p-3 bg-white border-0 shadow-sm">
                <div class="text-muted small fw-semibold text-uppercase">Total Farmers</div>
                <h3 class="fw-bold text-dark mb-0 mt-1">{{ $metrics['total_farmers'] }}</h3>
                <small class="text-success">{{ $activeFarmers->count() }} Approved</small>
            </div>
        </div>

        <div class="col-sm-6 col-lg">
            <div class="card card-custom p-3 bg-white border-0 shadow-sm">
                <div class="text-muted small fw-semibold text-uppercase">Customers</div>
                <h3 class="fw-bold text-dark mb-0 mt-1">{{ $metrics['total_customers'] }}</h3>
                <small class="text-muted">Registered shoppers</small>
            </div>
        </div>

        <div class="col-sm-6 col-lg">
            <div class="card card-custom p-3 bg-white border-0 shadow-sm">
                <div class="text-muted small fw-semibold text-uppercase">Markets</div>
                <h3 class="fw-bold text-dark mb-0 mt-1">{{ $metrics['total_markets'] }}</h3>
                <small class="text-muted">Active plazas</small>
            </div>
        </div>

        <div class="col-sm-6 col-lg">
            <div class="card card-custom p-3 bg-white border-0 shadow-sm">
                <div class="text-muted small fw-semibold text-uppercase">Total Orders</div>
                <h3 class="fw-bold text-dark mb-0 mt-1">{{ $metrics['total_orders'] }}</h3>
                <small class="text-muted">Pre-order reservations</small>
            </div>
        </div>

        <div class="col-sm-6 col-lg">
            <div class="card card-custom p-3 bg-white border-0 shadow-sm">
                <div class="text-muted small fw-semibold text-uppercase">Platform Volume</div>
                <h3 class="fw-bold text-success mb-0 mt-1">${{ number_format($metrics['total_volume'], 2) }}</h3>
                <small class="text-muted">Pay-at-pickup volume</small>
            </div>
        </div>
    </div>

    <!-- Pending Farmer Approvals Section (SRS §1.6: Admin can view, approve, or suspend Farmer registrations) -->
    <div class="card card-custom p-4 bg-white border-0 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="heading-serif fw-bold text-dark mb-0">Farmer Approval Gate</h5>
                <small class="text-muted">Farmers cannot publish weekly produce until approved by an administrator.</small>
            </div>
            <span class="badge bg-warning text-dark">{{ $pendingFarmers->count() }} Pending Review</span>
        </div>

        @if($pendingFarmers->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>Stall Name & Contact</th>
                            <th>Market Assigned</th>
                            <th>Phone & Email</th>
                            <th>Registration Date</th>
                            <th class="text-end">Approval Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingFarmers as $pf)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $pf->stall_name }}</div>
                                    <small class="text-muted">{{ $pf->contact_person }}</small>
                                </td>
                                <td>{{ $pf->market->name ?? 'Unassigned Market' }}</td>
                                <td>
                                    <div>{{ $pf->contact_number }}</div>
                                    <small class="text-muted">{{ $pf->user->email }}</small>
                                </td>
                                <td>{{ $pf->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <form action="{{ route('admin.farmers.approve', $pf->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">
                                            <i class="bi bi-check-lg me-1"></i> Approve
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-success mb-0 small">
                <i class="bi bi-check-circle-fill me-1"></i> All registered farmer stalls have been reviewed and approved!
            </div>
        @endif
    </div>

    <!-- Active Farmers & Customer Moderation -->
    <div class="row g-4">
        <!-- Active Farmers Table -->
        <div class="col-lg-6">
            <div class="card card-custom p-4 bg-white border-0 shadow-sm h-100">
                <h5 class="heading-serif fw-bold text-dark mb-3">Approved Farmer Stalls ({{ $activeFarmers->count() }})</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Stall</th>
                                <th>Market</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeFarmers as $af)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $af->stall_name }}</div>
                                        <span class="text-muted" style="font-size: 0.72rem;">{{ $af->contact_person }}</span>
                                    </td>
                                    <td>{{ $af->market->name ?? 'Local Market' }}</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.farmers.suspend', $af->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Suspend this farmer?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size: 0.75rem;">
                                                Suspend
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer Moderation Table (SRS §1.6) -->
        <div class="col-lg-6">
            <div class="card card-custom p-4 bg-white border-0 shadow-sm h-100">
                <h5 class="heading-serif fw-bold text-dark mb-3">Customer Accounts & Moderation</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Customer</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $c)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $c->name }}</div>
                                        <span class="text-muted" style="font-size: 0.72rem;">{{ $c->email }}</span>
                                    </td>
                                    <td>{{ $c->contact_number }}</td>
                                    <td>
                                        <span class="badge {{ $c->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $c->is_active ? 'Active' : 'Deactivated' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.customers.toggle', $c->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $c->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} py-0 px-2" style="font-size: 0.75rem;">
                                                {{ $c->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
