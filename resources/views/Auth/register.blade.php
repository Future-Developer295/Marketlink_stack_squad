@extends('Website._master')

@section('page_title', 'Register')

@section('page_styles')
    <link href="{{ asset('Assets/Website_Asset/css/auth.css') }}" rel="stylesheet">
@endsection

@section('body')

<div class="auth-page">
    <div class="auth-wrap">

        <nav class="auth-breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span>/</span>
            <span>Register</span>
        </nav>

        <div class="auth-shell is-wide">

            <aside class="auth-visual">
                <div class="auth-emblem">
                    <i class="fa-solid fa-seedling"></i>
                    JOIN THE<br>MARKET
                </div>

                <div>
                    <span class="auth-kicker"><span></span> JOIN MARKETLINK</span>

                    <h2>A little <em>local.</em><br>A lot of good.</h2>

                    <p>Reserve fresh produce and pick it up directly from local growers.</p>

                    <ul class="auth-points">
                        <li><i class="fa-solid fa-seedling"></i> Fresh picks straight from growers</li>
                        <li><i class="fa-solid fa-calendar-check"></i> Choose a pickup slot that suits you</li>
                        <li><i class="fa-solid fa-hand-holding-heart"></i> Pay at pickup, no online payment</li>
                    </ul>
                </div>

                <div class="auth-visual-note">Your next market morning starts here.</div>

                <span class="auth-orbit"></span>
            </aside>

            <div class="auth-panel">

                <div class="auth-panel-head">
                    <span class="auth-kicker"><span></span> REGISTER</span>
                    <h1>Create your <em>account</em></h1>
                    <p>It only takes a minute. Tell us a little about yourself.</p>
                </div>

                @include('Website.Partials.alerts')

                <form method="POST" action="{{ url('/register') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="ml-form-label" for="regName">Full Name</label>
                            <div class="auth-field @error('name') is-invalid @enderror">
                                <i class="fa-regular fa-user auth-icon"></i>
                                <input type="text" id="regName" name="name" value="{{ old('name') }}"
                                       class="form-control" placeholder="e.g., Tariq Mahmood"
                                       autocomplete="name" required autofocus>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="ml-form-label" for="regPhone">Phone Number</label>
                            <div class="auth-field @error('phone') is-invalid @enderror">
                                <i class="fa-solid fa-phone auth-icon"></i>
                                <input type="text" id="regPhone" name="phone" value="{{ old('phone') }}"
                                       class="form-control" placeholder="+92 300 0000000"
                                       autocomplete="tel" required>
                            </div>
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="ml-form-label" for="regEmail">Email Address</label>
                            <div class="auth-field @error('email') is-invalid @enderror">
                                <i class="fa-regular fa-envelope auth-icon"></i>
                                <input type="email" id="regEmail" name="email" value="{{ old('email') }}"
                                       class="form-control" placeholder="you@example.com"
                                       autocomplete="email" required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="ml-form-label" for="regAddress">Neighborhood / Area</label>
                            <div class="auth-field @error('address') is-invalid @enderror">
                                <i class="fa-solid fa-location-dot auth-icon"></i>
                                <input type="text" id="regAddress" name="address" value="{{ old('address') }}"
                                       class="form-control" placeholder="e.g., Clifton, Karachi"
                                       autocomplete="street-address" required>
                            </div>
                            @error('address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="ml-form-label" for="regPassword">Password</label>
                            <div class="auth-field has-toggle @error('password') is-invalid @enderror">
                                <i class="fa-solid fa-lock auth-icon"></i>
                                <input type="password" id="regPassword" name="password"
                                       class="form-control" placeholder="Create a password"
                                       autocomplete="new-password" required>
                                <button type="button" class="auth-toggle" data-toggle-password="#regPassword" aria-label="Show password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="ml-form-label" for="regPasswordConfirm">Confirm Password</label>
                            <div class="auth-field has-toggle">
                                <i class="fa-solid fa-shield-halved auth-icon"></i>
                                <input type="password" id="regPasswordConfirm" name="password_confirmation"
                                       class="form-control" placeholder="Re-enter password"
                                       autocomplete="new-password" required>
                                <button type="button" class="auth-toggle" data-toggle-password="#regPasswordConfirm" aria-label="Show password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12">
                            <span class="ml-form-label">I want to register as</span>

                            <div class="auth-roles">
                                <div>
                                    <input class="auth-role-input" type="radio" name="role" id="roleCustomer" value="customer" {{ old('role', 'customer') == 'customer' ? 'checked' : '' }}>
                                    <label class="auth-role-card" for="roleCustomer">
                                        <i class="fa-solid fa-basket-shopping"></i>
                                        <span>
                                            <strong>Customer</strong>
                                            <small>Reserve fresh produce</small>
                                        </span>
                                    </label>
                                </div>

                                <div>
                                    <input class="auth-role-input" type="radio" name="role" id="roleFarmer" value="farmer" {{ old('role') == 'farmer' ? 'checked' : '' }}>
                                    <label class="auth-role-card" for="roleFarmer">
                                        <i class="fa-solid fa-tractor"></i>
                                        <span>
                                            <strong>Farmer</strong>
                                            <small>Sell at local markets</small>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            @error('role')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                <label class="form-check-label small" for="agreeTerms">
                                    I agree to the
                                    <a href="{{ url('/terms') }}" class="auth-link">Terms &amp; Conditions</a>
                                    and
                                    <a href="{{ url('/privacy-policy') }}" class="auth-link">Privacy Policy</a>.
                                </label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="auth-submit mt-4">
                        <i class="fa-solid fa-user-plus"></i>
                        Create Account
                    </button>
                </form>

                <p class="auth-switch">
                    Already have an account?
                    <a href="{{ url('/login') }}" class="auth-link">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var input = document.querySelector(btn.getAttribute('data-toggle-password'));
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        btn.innerHTML = '<i class="fa-regular ' + (show ? 'fa-eye-slash' : 'fa-eye') + '"></i>';
    });
});
</script>

@endsection