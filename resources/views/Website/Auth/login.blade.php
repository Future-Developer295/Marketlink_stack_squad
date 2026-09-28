@extends('Website._master')

@section('page_title', 'Login')

@section('page_styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap');

        :root {
            --ml-green: #2f5d3a;
            --ml-green-dark: #173923;
            --ml-green-soft: #eaf2eb;
            --ml-cream: #f6f3ea;
            --ml-white: #ffffff;
            --ml-text: #18231c;
            --ml-muted: #6f786f;
            --ml-border: #dfe5de;
            --ml-accent: #9fb89f;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: var(--ml-cream);
            color: var(--ml-text);
            font-family: 'DM Sans', sans-serif;
        }

        .auth-page {
            min-height: 100vh;
            padding: 42px 20px;
            background:
                radial-gradient(circle at 8% 12%, rgba(47, 93, 58, 0.08), transparent 28%),
                radial-gradient(circle at 92% 88%, rgba(23, 57, 35, 0.07), transparent 26%),
                #f6f3ea;
        }

        .auth-wrap {
            width: 100%;
            max-width: 1080px;
            margin: 0 auto;
        }

        .auth-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            font-size: 13px;
            color: var(--ml-muted);
        }

        .auth-breadcrumb a {
            color: var(--ml-green);
            text-decoration: none;
            font-weight: 600;
        }

        .auth-breadcrumb a:hover {
            color: var(--ml-green-dark);
        }

        .auth-shell {
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            min-height: 640px;
            background: var(--ml-white);
            box-shadow: 0 28px 80px rgba(23, 57, 35, 0.11);
            overflow: hidden;
        }

        .auth-visual {
            background: linear-gradient(160deg, #21472d 0%, #173923 100%);
            color: #fff;
            padding: 54px 48px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .auth-visual::before,
        .auth-visual::after {
            content: '';
            position: absolute;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            pointer-events: none;
        }

        .auth-visual::before {
            width: 420px;
            height: 420px;
            top: -210px;
            right: -210px;
        }

        .auth-visual::after {
            width: 300px;
            height: 300px;
            bottom: -160px;
            left: -160px;
        }

        .auth-emblem {
            position: relative;
            z-index: 2;
            width: fit-content;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.8px;
            line-height: 1.5;
            color: #d7e5d8;
        }

        .auth-emblem i {
            margin-right: 8px;
            color: #aec9b0;
        }

        .auth-kicker {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--ml-green);
        }

        .auth-visual .auth-kicker {
            color: #b8cfb9;
        }

        .auth-kicker>span {
            width: 28px;
            height: 1px;
            background: currentColor;
            display: inline-block;
        }

        .auth-visual h2 {
            position: relative;
            z-index: 2;
            max-width: 480px;
            margin: 16px 0 18px;
            font-family: 'Playfair Display', serif;
            font-size: clamp(38px, 4vw, 54px);
            line-height: 1.08;
            font-weight: 700;
        }

        .auth-visual h2 em,
        .auth-panel h1 em {
            color: #b8cfb9;
            font-style: italic;
        }

        .auth-visual p {
            position: relative;
            z-index: 2;
            max-width: 460px;
            color: rgba(255, 255, 255, 0.68);
            font-size: 14px;
            line-height: 1.8;
            margin: 0;
        }

        .auth-points {
            list-style: none;
            padding: 0;
            margin: 36px 0 0;
            display: flex;
            flex-direction: column;
            gap: 18px;
            position: relative;
            z-index: 2;
        }

        .auth-points li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            color: rgba(255, 255, 255, 0.86);
            font-size: 14px;
            line-height: 1.55;
        }

        .auth-points i {
            width: 34px;
            height: 34px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.13);
            color: #bcd2bd;
        }

        .auth-visual-note {
            position: relative;
            z-index: 2;
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 15px;
            color: rgba(255, 255, 255, 0.55);
        }

        .auth-orbit {
            position: absolute;
            width: 130px;
            height: 130px;
            right: 30px;
            bottom: 26px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .auth-panel {
            padding: 58px 62px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #fff;
        }

        .auth-panel-head {
            margin-bottom: 30px;
        }

        .auth-panel h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(34px, 4vw, 46px);
            line-height: 1.12;
            margin: 10px 0 12px;
            color: var(--ml-text);
        }

        .auth-panel h1 em {
            color: var(--ml-green);
        }

        .auth-panel-head p {
            margin: 0;
            color: var(--ml-muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .ml-form-label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: 600;
            color: #3d473f;
        }

        .auth-field {
            min-height: 56px;
            display: flex;
            align-items: center;
            border: 1px solid var(--ml-border);
            background: #fff;
            transition: 0.25s ease;
            position: relative;
        }

        .auth-field:focus-within {
            border-color: var(--ml-green);
            box-shadow: 0 0 0 3px rgba(47, 93, 58, 0.07);
        }

        .auth-icon {
            width: 48px;
            text-align: center;
            color: #738076;
            flex-shrink: 0;
        }

        .auth-field .form-control {
            border: 0;
            box-shadow: none;
            background: transparent;
            min-height: 54px;
            padding: 0 14px 0 0;
            font-size: 14px;
            color: var(--ml-text);
        }

        .auth-field .form-control:focus {
            border: 0;
            box-shadow: none;
            background: transparent;
        }

        .auth-field .form-control::placeholder {
            color: #a1a8a2;
        }

        .auth-field.has-toggle .form-control {
            padding-right: 48px;
        }

        .auth-toggle {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            width: 38px;
            height: 38px;
            border: 0;
            background: transparent;
            color: #7f8880;
            cursor: pointer;
        }

        .auth-toggle:hover {
            color: var(--ml-green);
        }

        .auth-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
        }

        .form-check-input {
            border-color: #aeb7af;
        }

        .form-check-input:checked {
            background-color: var(--ml-green);
            border-color: var(--ml-green);
        }

        .form-check-input:focus {
            border-color: var(--ml-green);
            box-shadow: 0 0 0 0.2rem rgba(47, 93, 58, 0.12);
        }

        .auth-link {
            color: var(--ml-green);
            text-decoration: none;
            font-weight: 600;
        }

        .auth-link:hover {
            color: var(--ml-green-dark);
        }

        .auth-submit {
            width: 100%;
            min-height: 56px;
            border: 0;
            background: var(--ml-green);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: 0.25s ease;
        }

        .auth-submit:hover {
            background: var(--ml-green-dark);
            transform: translateY(-1px);
        }

        .auth-switch {
            text-align: center;
            margin: 24px 0 0;
            color: var(--ml-muted);
            font-size: 13px;
        }

        .invalid-feedback {
            font-size: 12px;
            margin-top: 6px;
        }

        @media (max-width: 1100px) {
            .auth-panel {
                padding: 48px 42px;
            }

            .auth-visual {
                padding: 48px 38px;
            }
        }

        @media (max-width: 991px) {
            .auth-page {
                padding: 28px 18px;
            }

            .auth-shell {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .auth-panel {
                order: 1;
                padding: 48px 44px;
            }

            .auth-visual {
                order: 2;
                min-height: 440px;
                padding: 48px 44px;
            }

            .auth-visual h2 {
                max-width: 650px;
            }

            .auth-visual p {
                max-width: 650px;
            }
        }

        @media (max-width: 767px) {
            .auth-page {
                padding: 18px 12px;
            }

            .auth-breadcrumb {
                margin-bottom: 12px;
            }

            .auth-panel {
                padding: 38px 28px;
            }

            .auth-visual {
                padding: 40px 28px;
                min-height: 400px;
            }
        }

        @media (max-width: 575px) {
            .auth-page {
                padding: 0;
            }

            .auth-wrap {
                max-width: 100%;
            }

            .auth-breadcrumb {
                padding: 14px 16px 0;
            }

            .auth-shell {
                box-shadow: none;
            }

            .auth-panel {
                padding: 34px 20px;
            }

            .auth-visual {
                padding: 38px 20px;
                min-height: 380px;
            }

            .auth-panel h1 {
                font-size: 30px;
            }

            .auth-visual h2 {
                font-size: 32px;
            }

            .auth-row {
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
            }

            .auth-field {
                min-height: 54px;
            }

            .auth-submit {
                min-height: 54px;
            }
        }
    </style>
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
                        <span class="auth-kicker">
                            <span></span>
                            WELCOME BACK
                        </span>

                        <h2>
                            Good things are
                            <em>waiting</em>
                            for you.
                        </h2>

                        <p>
                            Pick up where you left off: your basket, your favorite growers
                            and your next market morning.
                        </p>

                        <ul class="auth-points">
                            <li>
                                <i class="fa-solid fa-basket-shopping"></i>
                                Track your pre-orders from placed to pickup
                            </li>

                            <li>
                                <i class="fa-solid fa-heart"></i>
                                Keep your favorite products close
                            </li>

                            <li>
                                <i class="fa-solid fa-store"></i>
                                Meet the people behind your food
                            </li>
                        </ul>
                    </div>

                    <div class="auth-visual-note">
                        A little closer to the source.
                    </div>

                    <span class="auth-orbit"></span>
                </aside>

                <div class="auth-panel">

                    <div class="auth-panel-head">
                        <span class="auth-kicker">
                            <span></span>
                            LOGIN
                        </span>

                        <h1>
                            Login to
                            <em>MarketLink</em>
                        </h1>

                        <p>
                            Access your reservations, favorites and pickup history.
                        </p>
                    </div>

                    @include('Website.Partials.alerts')

                    <form method="POST" action="{{ url('/login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="ml-form-label" for="loginEmail">
                                Email Address
                            </label>

                            <div class="auth-field">
                                <i class="fa-regular fa-envelope auth-icon"></i>

                                <input type="email" id="loginEmail" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="you@example.com"
                                    autocomplete="email" required>
                            </div>

                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">

                            <label class="ml-form-label" for="loginPassword">
                                Password
                            </label>

                            <div class="auth-field has-toggle">
                                <i class="fa-solid fa-lock auth-icon"></i>

                                <input type="password" id="loginPassword" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter your password" autocomplete="current-password" required>

                                <button type="button" class="auth-toggle" data-toggle-password="#loginPassword"
                                    aria-label="Show password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>

                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="auth-row mb-4">

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember"
                                    {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label small" for="rememberMe">
                                    Remember me
                                </label>
                            </div>

                            <a href="{{ route('password.request') }}" class="small auth-link">
                                Forgot password?
                            </a>
                        </div>

                        <button type="submit" class="auth-submit">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            Login
                        </button>

                    </form>

                    <p class="auth-switch">
                        Don't have an account?
                        <a href="{{ url('/register') }}" class="auth-link">
                            Register
                        </a>
                    </p>

                </div>

            </div>

        </div>
    </div>

    <script>
        document.querySelectorAll('[data-toggle-password]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var input = document.querySelector(
                    btn.getAttribute('data-toggle-password')
                )

                var show = input.type === 'password'

                input.type = show ? 'text' : 'password'

                btn.setAttribute(
                    'aria-label',
                    show ? 'Hide password' : 'Show password'
                )

                btn.innerHTML =
                    '<i class="fa-regular ' +
                    (show ? 'fa-eye-slash' : 'fa-eye') +
                    '"></i>'
            })
        })
    </script>

@endsection
