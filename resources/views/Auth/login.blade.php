@extends('Website._master')

@section('page_title', 'Login')

@section('page_styles')
    <link href="{{ asset('Assets/Website_Asset/css/auth.css') }}" rel="stylesheet">
@endsection

@section('body')

<div class="auth-page">
    <div class="auth-wrap">

        <nav class="auth-breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span>/</span>
            <span>Login</span>
        </nav>

        <div class="auth-shell">

            <aside class="auth-visual">
                <div class="auth-emblem">
                    <i class="fa-solid fa-leaf"></i>
                    LOCAL<br>&amp; FRESH
                </div>

                <div>
                    <span class="auth-kicker"><span></span> WELCOME BACK</span>

                    <h2>Good things are <em>waiting</em> for you.</h2>

                    <p>Pick up where you left off: your basket, your favorite growers and your next market morning.</p>

                    <ul class="auth-points">
                        <li><i class="fa-solid fa-basket-shopping"></i> Track your pre-orders from placed to pickup</li>
                        <li><i class="fa-solid fa-heart"></i> Keep your favorite products close</li>
                        <li><i class="fa-solid fa-store"></i> Meet the people behind your food</li>
                    </ul>
                </div>

                <div class="auth-visual-note">A little closer to the source.</div>

                <span class="auth-orbit"></span>
            </aside>

            <div class="auth-panel">

                <div class="auth-panel-head">
                    <span class="auth-kicker"><span></span> LOGIN</span>
                    <h1>Login to <em>MarketLink</em></h1>
                    <p>Access your reservations, favorites and pickup history.</p>
                </div>

                @include('Website.Partials.alerts')

                <form method="POST" action="{{ url('/login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="ml-form-label" for="loginEmail">Email Address</label>

                        <div class="auth-field @error('email') is-invalid @enderror">
                            <i class="fa-regular fa-envelope auth-icon"></i>
                            <input type="email" id="loginEmail" name="email" value="{{ old('email') }}"
                                   class="form-control" placeholder="you@example.com"
                                   autocomplete="email" required autofocus>
                        </div>

                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="ml-form-label" for="loginPassword">Password</label>

                        <div class="auth-field has-toggle @error('password') is-invalid @enderror">
                            <i class="fa-solid fa-lock auth-icon"></i>
                            <input type="password" id="loginPassword" name="password"
                                   class="form-control" placeholder="Enter your password"
                                   autocomplete="current-password" required>
                            <button type="button" class="auth-toggle" data-toggle-password="#loginPassword" aria-label="Show password">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>

                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="auth-row mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small" for="rememberMe">Remember me</label>
                        </div>

                        <a href="{{ route('password.request') }}" class="small auth-link">Forgot password?</a>
                    </div>

                    <button type="submit" class="auth-submit">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Login
                    </button>
                </form>

                <p class="auth-switch">
                    Don't have an account?
                    <a href="{{ url('/register') }}" class="auth-link">Register</a>
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