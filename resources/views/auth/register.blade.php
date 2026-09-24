@extends('layouts.app')

@section('title', 'Create Account — MarketLink')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card card-custom p-4 p-md-5 bg-white border-0 shadow-sm">
                <div class="text-center mb-4">
                    <span class="p-3 bg-brand-light text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 58px; height: 58px;">
                        <i class="bi bi-person-plus fs-3"></i>
                    </span>
                    <h3 class="heading-serif fw-bold text-dark">Join MarketLink</h3>
                    <p class="text-muted small">Register as a local customer or as a farmers-market vendor stall</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger small py-2 mb-3">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <!-- Role Selection (SRS §1.6) -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-secondary d-block">I am registering as:</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="role" id="roleCustomer" value="customer" checked onchange="toggleFarmerFields()">
                                <label class="btn btn-outline-success w-100 py-2 d-flex flex-column align-items-center" for="roleCustomer">
                                    <i class="bi bi-basket2 fs-4 mb-1"></i>
                                    <strong>Customer / Shopper</strong>
                                    <span class="small" style="font-size: 0.75rem;">Pre-order for pickup</span>
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="role" id="roleFarmer" value="farmer" onchange="toggleFarmerFields()">
                                <label class="btn btn-outline-success w-100 py-2 d-flex flex-column align-items-center" for="roleFarmer">
                                    <i class="bi bi-shop fs-4 mb-1"></i>
                                    <strong>Farmer / Vendor</strong>
                                    <span class="small" style="font-size: 0.75rem;">Manage stall & weekly stock</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Farmer Stall Name (Conditional) -->
                    <div id="farmerFields" class="mb-3 p-3 bg-light rounded-3 border" style="display: none;">
                        <label class="form-label small fw-semibold text-dark">Stall or Farm Business Name</label>
                        <input type="text" name="stall_name" value="{{ old('stall_name') }}" class="form-control" placeholder="e.g. Hilltop Honey & Orchard">
                        <small class="text-muted d-block mt-1">
                            <i class="bi bi-shield-lock text-warning me-1"></i> <strong>Note:</strong> Farmer accounts require Admin approval before listings go live (SRS §1.6).
                        </small>
                    </div>

                    <!-- Full Name & Username -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Jane Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="janedoe" required>
                        </div>
                    </div>

                    <!-- Email & Contact Phone (SRS §1.6 mandatory fields) -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="jane@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Contact Phone</label>
                            <input type="tel" name="contact_number" value="{{ old('contact_number') }}" class="form-control" placeholder="+1 (555) 000-0000" required>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Address / Location</label>
                        <textarea name="address" rows="2" class="form-control" placeholder="Street address or neighborhood" required>{{ old('address') }}</textarea>
                    </div>

                    <!-- Password & Confirmation -->
                    <div class="row g-2 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-brand w-100 py-2 rounded-pill fw-semibold shadow-sm mb-3">
                        Create Account
                    </button>

                    <div class="text-center small text-secondary">
                        Already have an account? <a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none">Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleFarmerFields() {
        const isFarmer = document.getElementById('roleFarmer').checked;
        document.getElementById('farmerFields').style.display = isFarmer ? 'block' : 'none';
    }
</script>
@endsection
