@extends('layouts.app')

@section('title', 'My Saved Favorites — MarketLink')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Saved Favorites</li>
        </ol>
    </nav>

    <div class="mb-4">
        <span class="badge badge-brand px-3 py-1 rounded-pill mb-1">Customer Dashboard</span>
        <h2 class="heading-serif fw-bold text-dark mb-0">My Favorite Produce & Farmers</h2>
        <p class="text-muted small">Quick access to your preferred growers, weekly stock alerts, and favorite market stalls.</p>
    </div>

    <!-- Favorite Farmers -->
    <h4 class="heading-serif fw-bold text-dark mb-3">Favorite Farmer Stalls ({{ $farmers->count() }})</h4>
    <div class="row g-4 mb-5">
        @forelse($farmers as $farmer)
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-3 bg-white h-100 border-0 shadow-sm d-flex flex-column">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ $farmer->image_url ?: 'https://images.unsplash.com/photo-1595974482597-4b8da8879bc5?auto=format&fit=crop&w=150&q=80' }}" 
                             alt="{{ $farmer->stall_name }}" class="rounded-circle" style="width: 55px; height: 55px; object-fit: cover;">
                        <div>
                            <h6 class="fw-bold mb-0">{{ $farmer->stall_name }}</h6>
                            <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $farmer->market->name ?? 'Local Market' }}</small>
                        </div>
                    </div>
                    <p class="text-muted small flex-grow-1">{{ Str::limit($farmer->bio, 80) }}</p>
                    <a href="{{ route('farmers.show', $farmer->id) }}" class="btn btn-brand-outline btn-sm rounded-pill w-100 mt-auto">
                        View Stall Page
                    </a>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="p-3 bg-light rounded text-muted small">You haven't saved any favorite farmers yet.</div>
            </div>
        @endforelse
    </div>

    <!-- Favorite Products -->
    <h4 class="heading-serif fw-bold text-dark mb-3">Favorite Harvest Produce ({{ $products->count() }})</h4>
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card card-custom h-100 bg-white d-flex flex-column">
                    <img src="{{ $product->image_url ?: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=400&q=80' }}" 
                         class="card-img-top" style="height: 160px; object-fit: cover; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <div class="card-body p-3 d-flex flex-column">
                        <h6 class="fw-bold mb-1">
                            <a href="{{ route('products.show', $product->id) }}" class="text-dark text-decoration-none">
                                {{ $product->name }}
                            </a>
                        </h6>
                        <small class="text-success mb-2"><i class="bi bi-shop me-1"></i>{{ $product->farmer->stall_name }}</small>
                        <div class="fw-bold text-dark fs-5 mb-3">${{ number_format($product->price, 2) }} / {{ $product->unit }}</div>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-auto">
                            @csrf
                            <button type="submit" class="btn btn-brand-outline btn-sm w-100 rounded-pill">
                                <i class="bi bi-cart-plus me-1"></i> Pre-Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="p-3 bg-light rounded text-muted small">You haven't marked any produce as favorites yet.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection
