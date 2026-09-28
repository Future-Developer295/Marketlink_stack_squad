@extends('Website._master')

@section('page_title', 'Verify Email')

@section('page_styles')
<style>
.otp-page { min-height: 70vh; padding: 48px 16px; background: #f6f3ea; display: flex; align-items: center; justify-content: center; }
.otp-card { width: 100%; max-width: 460px; background: #fff; box-shadow: 0 28px 80px rgba(23,57,35,.11); padding: 42px 38px; }
.otp-icon { width: 58px; height: 58px; border-radius: 50%; background: #eaf2eb; color: #2f5d3a; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 18px; }
.otp-card h1 { font-size: 28px; font-weight: 700; color: #18231c; margin: 0 0 10px; }
.otp-card p.lead-text { color: #6f786f; font-size: 14px; line-height: 1.7; margin: 0 0 22px; }
.otp-card p.lead-text strong { color: #18231c; }
.otp-input { width: 100%; text-align: center; font-size: 32px; letter-spacing: 12px; font-weight: 700; padding: 12px 8px 12px 20px; border: 1px solid #dfe5de; color: #173923; outline: none; }
.otp-input:focus { border-color: #2f5d3a; box-shadow: 0 0 0 3px rgba(47,93,58,.08); }
.otp-submit { width: 100%; min-height: 54px; margin-top: 18px; border: 0; background: #2f5d3a; color: #fff; font-weight: 700; font-size: 14px; }
.otp-submit:hover { background: #173923; }
.otp-meta { display: flex; justify-content: space-between; align-items: center; margin-top: 18px; font-size: 13px; color: #6f786f; }
.otp-resend { border: 0; background: none; padding: 0; color: #2f5d3a; font-weight: 700; font-size: 13px; }
.otp-resend:disabled { color: #a1a8a2; cursor: not-allowed; }
.otp-back { display: block; text-align: center; margin-top: 22px; font-size: 13px; color: #2f5d3a; text-decoration: none; font-weight: 600; }
</style>
@endsection

@section('body')
<div class="otp-page">
    <div class="otp-card">
        <div class="otp-icon"><i class="fa-regular fa-envelope"></i></div>

        <h1>Verify your email</h1>
        <p class="lead-text">
            We sent a {{ \App\Services\EmailOtp::CODE_LENGTH }}-digit code to
            <strong>{{ $maskedEmail }}</strong>. It is valid for {{ $ttlMinutes }} minutes.
        </p>

        @include('Website.Partials.alerts')

        <form method="POST" action="{{ route('verification.otp.verify') }}">
            @csrf
            <input
                type="text"
                name="code"
                class="otp-input"
                inputmode="numeric"
                pattern="[0-9]*"
                maxlength="{{ \App\Services\EmailOtp::CODE_LENGTH }}"
                autocomplete="one-time-code"
                placeholder="------"
                autofocus
                required
            >
            <button type="submit" class="otp-submit">
                <i class="fa-solid fa-shield-check"></i> Verify &amp; Continue
            </button>
        </form>

        <div class="otp-meta">
            <span id="otpExpiry"></span>

            <form method="POST" action="{{ route('verification.otp.resend') }}" class="m-0">
                @csrf
                <button type="submit" class="otp-resend" id="otpResend" disabled>Resend code</button>
            </form>
        </div>

        <a href="{{ route('login') }}" class="otp-back">Back to login</a>
    </div>
</div>
@endsection

@section('page_scripts')
<script>
(function () {
    var expiresIn = {{ (int) $expiresIn }};
    var resendIn = {{ (int) $resendIn }};
    var expiryEl = document.getElementById('otpExpiry');
    var resendBtn = document.getElementById('otpResend');

    function pad(n) { return n < 10 ? '0' + n : n; }

    function tick() {
        if (expiresIn > 0) {
            expiryEl.textContent = 'Code expires in ' + Math.floor(expiresIn / 60) + ':' + pad(expiresIn % 60);
            expiresIn--;
        } else {
            expiryEl.textContent = 'Code expired. Please request a new one.';
        }

        if (resendIn > 0) {
            resendBtn.disabled = true;
            resendBtn.textContent = 'Resend code in ' + resendIn + 's';
            resendIn--;
        } else {
            resendBtn.disabled = false;
            resendBtn.textContent = 'Resend code';
        }
    }

    tick();
    setInterval(tick, 1000);
})();
</script>
@endsection
