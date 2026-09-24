@extends('layouts.app')

@section('title', 'MarketLink — Local Farmers Market Pre-Orders')

@section('content')

<!-- Hero Section -->
<section class="py-5 bg-white border-bottom position-relative overflow-hidden">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge badge-brand px-3 py-2 rounded-pill mb-3">
                    <i class="bi bi-patch-check-fill text-success me-1"></i> TechWiz 7 — Theme: eGreen Basket
                </span>
                <h1 class="display-4 fw-bold heading-serif text-dark mb-3">
                    Farm Fresh Produce, <br><span class="text-success">Just a Click Away</span>
                </h1>
                <p class="lead text-muted mb-4">
                    Connect directly with local farmers market vendors. Discover weekly harvests, view stalls on an interactive map, and pre-order for hassle-free in-person pickup.
                </p>

                <!-- Market Day Quick Finder Form -->
                <form action="{{ route('markets.index') }}" method="GET" class="card card-custom p-3 bg-light border-0 mb-4">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-7">
                            <label class="small text-muted fw-semibold mb-1"><i class="bi bi-calendar3 me-1"></i>Find Markets Open On:</label>
                            <select name="day" class="form-select border-0 shadow-sm">
                                <option value="">Select a Day (e.g. Saturday, Sunday)</option>
                                <option value="Saturday">Saturday Harvest Markets</option>
                                <option value="Sunday">Sunday Harvest Markets</option>
                                <option value="Wednesday">Wednesday Mid-week Markets</option>
                            </select>
                        </div>
                        <div class="col-md-5 pt-md-3">
                            <button type="submit" class="btn btn-brand w-100 py-2 shadow-sm">
                                <i class="bi bi-search me-1"></i> Locate Markets
                            </button>
                        </div>
                    </div>
                </form>

                <div class="d-flex flex-wrap gap-4 text-secondary small">
                    <div><i class="bi bi-check2-circle text-success fs-6 me-1"></i> <strong>In-Person Pickup</strong></div>
                    <div><i class="bi bi-check2-circle text-success fs-6 me-1"></i> <strong>Zero Online Fees</strong></div>
                    <div><i class="bi bi-check2-circle text-success fs-6 me-1"></i> <strong>OpenStreetMap Powered</strong></div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=900&q=80" 
                         alt="Fresh Farmers Market" 
                         class="img-fluid rounded-4 shadow-lg w-100" 
                         style="max-height: 480px; object-fit: cover;">
                    <div class="position-absolute bottom-0 start-0 bg-white p-3 m-3 rounded-3 shadow-lg border d-none d-sm-flex align-items-center gap-3">
                        <div class="bg-success text-white p-3 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Pay at Stall Pickup</h6>
                            <small class="text-muted">Direct vendor settlement — SRS Compliant</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Active Platform Announcements (SRS §1.6) -->
@if($announcements->count() > 0)
<section class="py-3 bg-brand-light border-bottom">
    <div class="container">
        @foreach($announcements as $ann)
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-success px-2 py-1 text-uppercase">{{ $ann->badge_type }}</span>
                <span class="fw-semibold text-dark">{{ $ann->title }}:</span>
                <span class="text-muted small">{{ $ann->content }}</span>
            </div>
        @endforeach
    </div>
</section>
@endif

<!-- Categories Grid -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-success fw-bold text-uppercase small">Browse Categories</span>
                <h2 class="heading-serif fw-bold text-dark mb-0">Seasonal Goodness</h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-brand-outline btn-sm rounded-pill px-3">
                View All Categories <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3">
            @foreach($categories as $cat)
                <div class="col-6 col-md-4 col-lg">
                    <a href="{{ route('products.index', ['category' => $cat->id]) }}" class="text-decoration-none">
                        <div class="card card-custom h-100 text-center p-3 border-0 bg-white">
                            <div class="mx-auto mb-2 p-3 bg-brand-light text-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 58px; height: 58px;">
                                <i class="bi {{ $cat->icon ?: 'bi-basket2' }} fs-3"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">{{ $cat->name }}</h6>
                            <small class="text-muted">{{ $cat->products_count }} Products</small>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Fresh Produce -->
<section class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-success fw-bold text-uppercase small">This Week's Stock</span>
                <h2 class="heading-serif fw-bold text-dark mb-0">Harvested from Local Growers</h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-brand btn-sm rounded-pill px-3">
                Full Catalog <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($featuredProducts as $product)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card card-custom h-100 d-flex flex-column">
                        <div class="position-relative">
                            <img src="{{ $product->image_url ?: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=600&q=80' }}" 
                                 class="card-img-top" 
                                 alt="{{ $product->name }}" 
                                 style="height: 180px; object-fit: cover; border-top-left-radius: 12px; border-top-right-left: 12px;">
                            <span class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-75">
                                {{ $product->category->name }}
                            </span>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <small class="text-success fw-semibold mb-1">
                                <i class="bi bi-shop me-1"></i>{{ $product->farmer->stall_name }}
                            </small>
                            <h6 class="card-title fw-bold text-dark mb-2">
                                <a href="{{ route('products.show', $product->id) }}" class="text-dark text-decoration-none">
                                    {{ $product->name }}
                                </a>
                            </h6>
                            <div class="d-flex align-items-baseline gap-1 mb-2">
                                <span class="fs-5 fw-bold text-dark">${{ number_format($product->price, 2) }}</span>
                                <span class="text-muted small">/ {{ $product->unit }}</span>
                            </div>
                            <p class="text-muted small flex-grow-1 text-truncate-2 mb-3">
                                {{ Str::limit($product->description, 65) }}
                            </p>
                            
                            <div class="mt-auto d-flex gap-2">
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="w-100">
                                    @csrf
                                    <button type="submit" class="btn btn-brand-outline w-100 btn-sm rounded-pill py-2">
                                        <i class="bi bi-cart-plus me-1"></i> Pre-Order
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Map & Discovery Feature Banner -->
<section class="py-5 bg-brand-light">
    <div class="container py-3">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <span class="badge bg-success text-white px-3 py-1 rounded-pill mb-2">Interactive OpenStreetMap</span>
                <h2 class="heading-serif fw-bold text-dark mb-3">
                    Locate Markets & Stall Pickup Points
                </h2>
                <p class="text-muted mb-4">
                    Never arrive to a closed market again. Explore interactive map markers for community markets, view exact stall locations, operating days, and get pickup directions directly to the vendor's booth.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('markets.index') }}" class="btn btn-brand px-4 py-2 rounded-pill">
                        <i class="bi bi-map me-1"></i> Open Interactive Map
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-custom p-2 bg-white border-0 shadow">
                    <div class="p-3 bg-light rounded-3 text-center">
                        <i class="bi bi-geo-alt-fill text-danger display-4 mb-2"></i>
                        <h5 class="fw-bold mb-1">Live Stall Discovery</h5>
                        <p class="text-muted small mb-0">OpenStreetMap integration powered by Leaflet.js</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
