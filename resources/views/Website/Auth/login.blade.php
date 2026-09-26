@extends('Website._master')

@section('page_title', 'Login')

@section('body')

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <span class="active">Login</span></nav>
</div>

<section class="ml-section pt-2">
    <div class="ml-container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="ml-card">
                    <div class="text-center mb-4">
                        <span class="ml-eyebrow"><i class="fa-solid fa-lock"></i> Welcome Back</span>
                        <h2>Login to MarketLink</h2>
                        <p class="text-muted small">Access your reservations, favorites and pickup history.</p>
                    </div>

                    @include('Website.Partials.alerts')

                    <form method="POST" action="{{ url('/login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="ml-form-label">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="you@example.com" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="ml-form-label">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="rememberMe">Remember me</label>
                            </div>
                            <a href="{{ url('/password/reset') }}" class="small ml-btn-link">Forgot password?</a>
                        </div>
                        <button type="submit" class="btn ml-btn-primary ml-btn-block"><i class="fa-solid fa-right-to-bracket"></i> Login</button>
                    </form>

                    <p class="text-center small text-muted mt-4 mb-0">
                        Don't have an account? <a href="{{ url('/register') }}" class="ml-btn-link">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
