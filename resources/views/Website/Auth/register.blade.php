@extends('Website._master')

@section('page_title', 'Register')

@section('body')

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <span class="active">Register</span></nav>
</div>

<section class="ml-section pt-2">
    <div class="ml-container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="ml-card">
                    <div class="text-center mb-4">
                        <span class="ml-eyebrow"><i class="fa-solid fa-user-plus"></i> Join MarketLink</span>
                        <h2>Create Your Account</h2>
                        <p class="text-muted small">Reserve fresh produce and pick it up directly from local growers.</p>
                    </div>

                    @include('Website.Partials.alerts')

                    <form method="POST" action="{{ url('/register') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="ml-form-label">Full Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="e.g., Tariq Mahmood" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="ml-form-label">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="+92 300 0000000" required>
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="ml-form-label">Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="you@example.com" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="ml-form-label">Neighborhood / Area</label>
                                <input type="text" name="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror" placeholder="e.g., Clifton, Karachi" required>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="ml-form-label">Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Create a password" required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="ml-form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter password" required>
                            </div>
                            <div class="col-12">
                                <label class="ml-form-label">I want to register as</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="role" id="roleCustomer" value="customer" {{ old('role', 'customer') == 'customer' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="roleCustomer">Customer</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="role" id="roleFarmer" value="farmer" {{ old('role') == 'farmer' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="roleFarmer">Farmer</label>
                                    </div>
                                </div>
                                @error('role')
                                <div class="small text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                    <label class="form-check-label small" for="agreeTerms">I agree to the <a href="{{ url('/terms') }}" class="ml-btn-link">Terms &amp; Conditions</a> and <a href="{{ url('/privacy-policy') }}" class="ml-btn-link">Privacy Policy</a>.</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn ml-btn-primary ml-btn-block mt-4"><i class="fa-solid fa-user-plus"></i> Create Account</button>
                    </form>

                    <p class="text-center small text-muted mt-4 mb-0">
                        Already have an account? <a href="{{ url('/login') }}" class="ml-btn-link">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
