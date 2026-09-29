@extends('Website._master')

@section('page_title', 'Register')

@section('page_styles')
    <link href="{{ asset('Assets/Website_Asset/css/auth.css') }}" rel="stylesheet">
@endsection

@section('body')

<div class="auth-page">

    <nav class="auth-breadcrumb">
        <a href="{{ url('/') }}">Home</a>
        <span>/</span>
        <span>Register</span>
    </nav>

    <div class="auth-wrap is-wide">
        <div class="auth-card is-wide">
{{-- =====================================================================
     Replace the existing <aside class="auth-visual"> ... </aside> block
     in register.blade.php with this. It adds a market-stall illustration
     (matching the Markets page hero art) into the empty space between the
     leaf pattern and the "A little local..." copy.
     ===================================================================== --}}
<aside class="auth-visual">
    <a href="{{ url('/') }}" class="auth-back" aria-label="Back to home">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <span class="auth-emblem"><i class="fa-solid fa-seedling"></i> JOIN THE MARKET</span>

    <div class="auth-leaves" aria-hidden="true">
        <i class="fa-solid fa-leaf l1"></i>
        <i class="fa-solid fa-leaf l2"></i>
        <i class="fa-solid fa-leaf l3"></i>
        <i class="fa-solid fa-leaf l4"></i>
        <i class="fa-solid fa-seedling l5"></i>
        <i class="fa-solid fa-leaf l6"></i>
    </div>

    {{-- Market-stall illustration filling the empty middle space --}}
    <div class="auth-stall-art" aria-hidden="true">
        <svg viewBox="0 0 300 240" xmlns="http://www.w3.org/2000/svg">
            <!-- soft sun glow -->
            <circle cx="248" cy="42" r="34" fill="#ffffff14"/>
            <circle cx="248" cy="42" r="20" fill="#dcecac33"/>

            <!-- posts -->
            <rect x="58" y="100" width="7" height="118" rx="3" fill="#cdbb8a"/>
            <rect x="235" y="100" width="7" height="118" rx="3" fill="#cdbb8a"/>

            <!-- awning -->
            <path d="M34 116 L62 66 H238 L266 116 Z" fill="#dcecac"/>
            <path d="M62 66 H238 L266 116 H34 Z" fill="none"/>
            <g fill="#40572c">
                <circle cx="48" cy="116" r="14"/><circle cx="90" cy="116" r="14"/>
                <circle cx="132" cy="116" r="14"/><circle cx="174" cy="116" r="14"/>
                <circle cx="216" cy="116" r="14"/><circle cx="252" cy="116" r="14"/>
            </g>
            <rect x="60" y="60" width="180" height="7" rx="3.5" fill="#1c2c17"/>

            <!-- hanging sign -->
            <line x1="130" y1="132" x2="130" y2="150" stroke="#cdbb8a" stroke-width="2"/>
            <line x1="172" y1="132" x2="172" y2="150" stroke="#cdbb8a" stroke-width="2"/>
            <rect x="112" y="147" width="78" height="26" rx="8" fill="#fbfaf1"/>
            <text x="151" y="164" text-anchor="middle" font-family="Georgia,serif" font-style="italic" font-size="11" fill="#40572c">Fresh today</text>

            <!-- counter -->
            <rect x="46" y="196" width="208" height="30" rx="6" fill="#c9b481"/>
            <rect x="46" y="196" width="208" height="8" rx="4" fill="#dcecac"/>

            <!-- carrot -->
            <g>
                <path d="M92 196 L100 170 L108 196 Z" fill="#e2924f"/>
                <path d="M100 170 l-4 -9 M100 170 l4 -10 M100 170 l8 -6" stroke="#8fae3f" stroke-width="3" stroke-linecap="round"/>
            </g>
            <!-- apple -->
            <g>
                <circle cx="150" cy="185" r="15" fill="#c2503a"/>
                <path d="M150 170 l4 -7" stroke="#6b4a32" stroke-width="2.5" stroke-linecap="round"/>
                <path d="M152 166 C160 162 165 168 160 173" fill="none" stroke="#8fae3f" stroke-width="3" stroke-linecap="round"/>
            </g>
            <!-- seedling -->
            <g>
                <path d="M204 196 C204 178 218 172 222 160 C214 168 202 168 200 180 C198 168 186 166 180 156 C182 172 194 178 200 196Z" fill="#8fae3f"/>
            </g>

            <!-- ground shadow -->
            <ellipse cx="150" cy="230" rx="118" ry="8" fill="#00000022"/>
        </svg>
    </div>

    <div class="auth-visual-copy">
        <span class="auth-kicker"><span></span> JOIN MARKETLINK</span>
        <h2>A little <em style="color:var(--lime)">local.</em><br>A lot of good.</h2>
        <p>Reserve fresh produce and pick it up directly from local growers.</p>

        <ul class="auth-points">
            <li><i class="fa-solid fa-seedling"></i> Fresh picks straight from growers</li>
            <li><i class="fa-solid fa-calendar-check"></i> Choose a pickup slot that suits you</li>
            <li><i class="fa-solid fa-hand-holding-heart"></i> Pay at pickup, no online payment</li>
        </ul>
    </div>
</aside>

            <div class="auth-sheet">

                <div class="auth-panel-head">
                    <span class="auth-kicker"><span></span> REGISTER</span>
                    <h1>Create your account</h1>
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