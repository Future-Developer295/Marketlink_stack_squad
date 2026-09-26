<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Log in') }} - MarketLink</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        .ml-login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef1ee;
            font-family: 'Figtree', 'Segoe UI', sans-serif;
            padding: 28px;
            box-sizing: border-box;
        }
        .ml-login-page *, .ml-login-page *::before, .ml-login-page *::after { box-sizing: border-box; }

        .ml-login-shell {
            width: 100%;
            max-width: 1220px;
            min-height: 660px;
            background: #ffffff;
            border-radius: 28px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 24px 70px rgba(11, 43, 36, 0.10);
        }

        /* Left panel */
        .ml-login-left {
            flex: 0 0 42%;
            background: #073f3c;
            padding: 48px 56px;
            display: flex;
            flex-direction: column;
            color: #fff;
        }
        .ml-login-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .ml-login-brand-badge {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: #35c978;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .ml-login-brand-badge svg { width: 26px; height: 26px; color: #fff; }
        .ml-login-brand-name {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.01em;
            color: #ffffff;
        }
        .ml-login-content {
            margin-top: auto;
            margin-bottom: auto;
            padding: 90px 0 40px;
            max-width: 420px;
        }
        .ml-login-heading {
            font-size: 40px;
            line-height: 1.18;
            font-weight: 800;
            color: #ffffff;
            margin: 0 0 18px;
            letter-spacing: -0.01em;
        }
        .ml-login-sub {
            font-size: 16px;
            line-height: 1.6;
            color: #a9c9bd;
            margin: 0;
        }

        /* Right panel */
        .ml-login-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fbfcfb;
            padding: 40px 32px;
        }
        .ml-login-card {
            width: 100%;
            max-width: 460px;
            background: #ffffff;
            border: 1px solid #eef2ef;
            border-radius: 22px;
            padding: 44px 40px;
            box-shadow: 0 10px 40px rgba(15, 43, 38, 0.06);
        }
        .ml-login-title {
            font-size: 30px;
            font-weight: 800;
            color: #0f2b25;
            margin: 0 0 6px;
            letter-spacing: -0.01em;
        }
        .ml-login-desc {
            font-size: 15px;
            color: #7c8f87;
            margin: 0 0 26px;
        }

        .ml-login-status {
            margin-bottom: 18px;
            padding: 11px 14px;
            border-radius: 10px;
            background: #eaf6ef;
            color: #1c7a4c;
            font-size: 13.5px;
            font-weight: 600;
        }
        .ml-login-errors {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 10px;
            background: #fceeed;
            border: 1px solid #f3d3d1;
        }
        .ml-login-errors p { margin: 0 0 6px; font-weight: 700; color: #b54748; font-size: 13.5px; }
        .ml-login-errors ul { margin: 0; padding-left: 18px; }
        .ml-login-errors li { color: #b54748; font-size: 13px; }

        .ml-field { margin-bottom: 20px; }
        .ml-field label {
            display: block;
            font-size: 13.5px;
            font-weight: 700;
            color: #16241f;
            margin-bottom: 8px;
        }
        .ml-input-wrap { position: relative; }
        .ml-input-wrap svg.ml-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 19px;
            height: 19px;
            color: #8a9c95;
            pointer-events: none;
        }
        .ml-input-wrap input {
            width: 100%;
            height: 50px;
            padding: 0 16px 0 44px;
            border: 1.5px solid #e3e8e5;
            border-radius: 12px;
            font-size: 14.5px;
            color: #16241f;
            background: #fff;
            outline: none;
            transition: border-color .15s ease, box-shadow .15s ease;
            font-family: inherit;
        }
        .ml-input-wrap input::placeholder { color: #9fb0aa; }
        .ml-input-wrap input:focus {
            border-color: #35c978;
            box-shadow: 0 0 0 3px rgba(53, 201, 120, 0.18);
        }

        .ml-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 4px 0 26px;
        }
        .ml-remember {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 14px;
            color: #46564f;
            cursor: pointer;
            user-select: none;
        }
        .ml-remember input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            border: 1.5px solid #cbd6d0;
            accent-color: #073f3c;
            cursor: pointer;
        }
        .ml-forgot {
            font-size: 14px;
            font-weight: 600;
            color: #128a5d;
            text-decoration: none;
        }
        .ml-forgot:hover { text-decoration: underline; }

        .ml-submit {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 12px;
            background: #073f3c;
            color: #ffffff;
            font-size: 15.5px;
            font-weight: 700;
            cursor: pointer;
            transition: background .15s ease;
            font-family: inherit;
        }
        .ml-submit:hover { background: #0a4d48; }
        .ml-submit:focus-visible { outline: 3px solid rgba(53, 201, 120, 0.35); outline-offset: 2px; }

        .ml-secure {
            margin-top: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 13px;
            color: #8a9c95;
        }
        .ml-secure svg { width: 14px; height: 14px; }

        @media (max-width: 900px) {
            .ml-login-shell { flex-direction: column; min-height: auto; max-width: 480px; }
            .ml-login-left { flex: none; padding: 36px 32px; }
            .ml-login-content { padding: 30px 0 8px; margin: 0; }
            .ml-login-heading { font-size: 30px; }
            .ml-login-right { padding: 32px 24px; }
            .ml-login-card { padding: 34px 26px; box-shadow: none; border: none; }
        }
    </style>
</head>
<body>
    <div class="ml-login-page">
        <div class="ml-login-shell">
            <!-- Left brand / info panel -->
            <div class="ml-login-left">
                <div class="ml-login-brand">
                    <div class="ml-login-brand-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/>
                            <path d="M2.6 12.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83"/>
                            <path d="M2.6 17.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83"/>
                        </svg>
                    </div>
                    <div class="ml-login-brand-name">MarketLink</div>
                </div>

                <div class="ml-login-content">
                    <h1 class="ml-login-heading">Welcome to<br>MarketLink.</h1>
                    <p class="ml-login-sub">Manage your market, products and orders in one place.</p>
                </div>
            </div>

            <!-- Right login form panel -->
            <div class="ml-login-right">
                <div class="ml-login-card">
                    <h2 class="ml-login-title">Welcome back</h2>
                    <p class="ml-login-desc">Sign in to your MarketLink account.</p>

                    @if ($errors->any())
                        <div class="ml-login-errors">
                            <p>{{ __('Whoops! Something went wrong.') }}</p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @session('status')
                        <div class="ml-login-status">{{ $value }}</div>
                    @endsession

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="ml-field">
                            <label for="email">{{ __('Email address') }}</label>
                            <div class="ml-input-wrap">
                                <svg class="ml-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                                    <path d="m22 6-10 7L2 6"/>
                                </svg>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus autocomplete="username">
                            </div>
                        </div>

                        <div class="ml-field">
                            <label for="password">{{ __('Password') }}</label>
                            <div class="ml-input-wrap">
                                <svg class="ml-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                <input id="password" type="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                            </div>
                        </div>

                        <div class="ml-row">
                            <label class="ml-remember" for="remember_me">
                                <input id="remember_me" type="checkbox" name="remember">
                                <span>{{ __('Remember me') }}</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="ml-forgot" href="{{ route('password.request') }}">{{ __('Forgot password?') }}</a>
                            @endif
                        </div>

                        <button type="submit" class="ml-submit">{{ __('Log in') }}</button>

                        <div class="ml-secure">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <span>{{ __('Secure access to your dashboard') }}</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
