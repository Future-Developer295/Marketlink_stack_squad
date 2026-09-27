<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Register') }} - MarketLink</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
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
            padding: 60px 0 40px;
            max-width: 420px;
        }
        .ml-login-heading {
            font-size: 38px;
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
            margin: 0 0 34px;
        }
        .ml-login-points { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 16px; }
        .ml-login-points li { display: flex; align-items: flex-start; gap: 12px; font-size: 14.5px; color: #cfe4da; }
        .ml-login-points svg { width: 20px; height: 20px; flex-shrink: 0; color: #35c978; margin-top: 1px; }

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
            max-width: 500px;
            background: #ffffff;
            border: 1px solid #eef2ef;
            border-radius: 22px;
            padding: 40px 40px;
            box-shadow: 0 10px 40px rgba(15, 43, 38, 0.06);
        }
        .ml-login-title {
            font-size: 28px;
            font-weight: 800;
            color: #0f2b25;
            margin: 0 0 6px;
            letter-spacing: -0.01em;
        }
        .ml-login-desc {
            font-size: 15px;
            color: #7c8f87;
            margin: 0 0 24px;
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

        /* Role toggle */
        .ml-role-label {
            display: block;
            font-size: 13.5px;
            font-weight: 700;
            color: #16241f;
            margin-bottom: 10px;
        }
        .ml-role-toggle {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 22px;
        }
        .ml-role-option { position: relative; }
        .ml-role-option input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            margin: 0;
            cursor: pointer;
        }
        .ml-role-card {
            display: flex;
            align-items: center;
            gap: 11px;
            border: 1.5px solid #e3e8e5;
            border-radius: 14px;
            padding: 14px 14px;
            transition: border-color .15s ease, background .15s ease, box-shadow .15s ease;
            background: #fff;
        }
        .ml-role-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #eaf6ef;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #073f3c;
            transition: background .15s ease, color .15s ease;
        }
        .ml-role-icon svg { width: 19px; height: 19px; }
        .ml-role-text-title { font-size: 14.5px; font-weight: 700; color: #16241f; }
        .ml-role-text-sub { font-size: 12px; color: #8a9c95; margin-top: 1px; }
        .ml-role-option input:checked ~ .ml-role-card {
            border-color: #35c978;
            background: #f4fbf6;
            box-shadow: 0 0 0 3px rgba(53, 201, 120, 0.14);
        }
        .ml-role-option input:checked ~ .ml-role-card .ml-role-icon {
            background: #073f3c;
            color: #ffffff;
        }
        .ml-role-option input:focus-visible ~ .ml-role-card {
            outline: 2px solid #35c978;
            outline-offset: 2px;
        }

        .ml-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .ml-field { margin-bottom: 18px; }
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
            top: 18px;
            width: 19px;
            height: 19px;
            color: #8a9c95;
            pointer-events: none;
        }
        .ml-input-wrap textarea ~ svg.ml-icon { top: 16px; }
        .ml-input-wrap input,
        .ml-input-wrap textarea {
            width: 100%;
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
        .ml-input-wrap input { height: 50px; }
        .ml-input-wrap textarea { padding-top: 14px; padding-bottom: 14px; min-height: 72px; resize: vertical; line-height: 1.5; }
        .ml-input-wrap input::placeholder,
        .ml-input-wrap textarea::placeholder { color: #9fb0aa; }
        .ml-input-wrap input:focus,
        .ml-input-wrap textarea:focus {
            border-color: #35c978;
            box-shadow: 0 0 0 3px rgba(53, 201, 120, 0.18);
        }

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
            margin-top: 6px;
        }
        .ml-submit:hover { background: #0a4d48; }
        .ml-submit:focus-visible { outline: 3px solid rgba(53, 201, 120, 0.35); outline-offset: 2px; }

        .ml-login-link {
            margin-top: 18px;
            text-align: center;
            font-size: 14px;
            color: #7c8f87;
        }
        .ml-login-link a {
            color: #128a5d;
            font-weight: 700;
            text-decoration: none;
        }
        .ml-login-link a:hover { text-decoration: underline; }

        @media (max-width: 900px) {
            .ml-login-shell { flex-direction: column; min-height: auto; max-width: 520px; }
            .ml-login-left { flex: none; padding: 36px 32px; }
            .ml-login-content { padding: 26px 0 6px; margin: 0; }
            .ml-login-heading { font-size: 28px; }
            .ml-login-points { display: none; }
            .ml-login-right { padding: 32px 24px; }
            .ml-login-card { padding: 30px 22px; box-shadow: none; border: none; }
            .ml-grid-2, .ml-role-toggle { grid-template-columns: 1fr; }
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
                    <h1 class="ml-login-heading">Join MarketLink<br>today.</h1>
                    <p class="ml-login-sub">Create an account as a shopper or as a farmer selling at the market.</p>

                    <ul class="ml-login-points">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            <span>Customers can browse markets and place orders</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            <span>Farmers can list products and manage stock</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            <span>One account, secure and easy to manage</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right register form panel -->
            <div class="ml-login-right">
                <div class="ml-login-card">
                    <h2 class="ml-login-title">Create your account</h2>
                    <p class="ml-login-desc">Sign up to get started with MarketLink.</p>

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

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <label class="ml-role-label">{{ __('I am registering as') }}</label>
                        <div class="ml-role-toggle">
                            <div class="ml-role-option">
                                <input type="radio" name="role" id="role_customer" value="customer" {{ old('role', 'customer') == 'customer' ? 'checked' : '' }} required>
                                <label for="role_customer" class="ml-role-card">
                                    <span class="ml-role-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                                        </svg>
                                    </span>
                                    <span>
                                        <span class="ml-role-text-title" style="display:block;">Customer</span>
                                        <span class="ml-role-text-sub" style="display:block;">Shop at the market</span>
                                    </span>
                                </label>
                            </div>
                            <div class="ml-role-option">
                                <input type="radio" name="role" id="role_farmer" value="farmer" {{ old('role') == 'farmer' ? 'checked' : '' }} required>
                                <label for="role_farmer" class="ml-role-card">
                                    <span class="ml-role-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22s8-4.5 8-11a8 8 0 1 0-16 0c0 6.5 8 11 8 11Z"/>
                                            <circle cx="12" cy="11" r="3"/>
                                        </svg>
                                    </span>
                                    <span>
                                        <span class="ml-role-text-title" style="display:block;">Farmer</span>
                                        <span class="ml-role-text-sub" style="display:block;">Sell your produce</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="ml-field">
                            <label for="name">{{ __('Full name') }}</label>
                            <div class="ml-input-wrap">
                                <svg class="ml-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required autofocus autocomplete="name">
                            </div>
                        </div>

                        <div class="ml-grid-2">
                            <div class="ml-field">
                                <label for="email">{{ __('Email address') }}</label>
                                <div class="ml-input-wrap">
                                    <svg class="ml-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/>
                                    </svg>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autocomplete="username">
                                </div>
                            </div>

                            <div class="ml-field">
                                <label for="phone">{{ __('Phone number') }}</label>
                                <div class="ml-input-wrap">
                                    <svg class="ml-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="03xx-xxxxxxx" required autocomplete="tel">
                                </div>
                            </div>
                        </div>

                        <div class="ml-field">
                            <label for="address">{{ __('Address') }}</label>
                            <div class="ml-input-wrap">
                                <svg class="ml-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                                </svg>
                                <textarea id="address" name="address" placeholder="House / street, city, area" required autocomplete="street-address">{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <div class="ml-grid-2">
                            <div class="ml-field">
                                <label for="password">{{ __('Password') }}</label>
                                <div class="ml-input-wrap">
                                    <svg class="ml-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                    <input id="password" type="password" name="password" placeholder="Create a password" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="ml-field">
                                <label for="password_confirmation">{{ __('Confirm password') }}</label>
                                <div class="ml-input-wrap">
                                    <svg class="ml-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Re-enter password" required autocomplete="new-password">
                                </div>
                            </div>
                        </div>

                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div class="ml-field" style="display:flex; align-items:flex-start; gap:9px; font-size:13.5px; color:#46564f;">
                                <input type="checkbox" name="terms" id="terms" required style="margin-top:3px; accent-color:#073f3c;">
                                <label for="terms" style="font-weight:500;">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" style="color:#128a5d; font-weight:600; text-decoration:none;">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" style="color:#128a5d; font-weight:600; text-decoration:none;">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                                </label>
                            </div>
                        @endif

                        <button type="submit" class="ml-submit">{{ __('Create account') }}</button>

                        <p class="ml-login-link">
                            {{ __('Already have an account?') }}
                            <a href="{{ route('login') }}">{{ __('Log in') }}</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
