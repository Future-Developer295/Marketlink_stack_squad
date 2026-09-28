@extends('Website._master')

@section('page_title', 'Reset Password')

@section('page_styles')
<style>
.fp-page { min-height: 70vh; padding: 48px 16px; background: #f6f3ea; display: flex; align-items: center; justify-content: center; }
.fp-card { width: 100%; max-width: 460px; background: #fff; box-shadow: 0 28px 80px rgba(23,57,35,.11); padding: 42px 38px; }
.fp-icon { width: 58px; height: 58px; border-radius: 50%; background: #eaf2eb; color: #2f5d3a; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 18px; }
.fp-card h1 { font-size: 28px; font-weight: 700; color: #18231c; margin: 0 0 10px; }
.fp-card p.lead-text { color: #6f786f; font-size: 14px; line-height: 1.7; margin: 0 0 22px; }
.fp-card label { display: block; font-size: 13px; font-weight: 600; color: #18231c; margin: 0 0 6px; }
.fp-input { width: 100%; padding: 13px 14px; border: 1px solid #dfe5de; font-size: 15px; color: #18231c; outline: none; margin-bottom: 16px; }
.fp-input:focus { border-color: #2f5d3a; box-shadow: 0 0 0 3px rgba(47,93,58,.08); }
.fp-submit { width: 100%; min-height: 54px; margin-top: 4px; border: 0; background: #2f5d3a; color: #fff; font-weight: 700; font-size: 14px; }
.fp-submit:hover { background: #173923; }
.fp-back { display: block; text-align: center; margin-top: 22px; font-size: 13px; color: #2f5d3a; text-decoration: none; font-weight: 600; }
</style>
@endsection

@section('body')
<div class="fp-page">
    <div class="fp-card">
        <div class="fp-icon"><i class="fa-solid fa-lock"></i></div>

        <h1>Set a new password</h1>
        <p class="lead-text">Choose a strong password for your account.</p>

        @include('Website.Partials.alerts')

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <label for="email">Email address</label>
            <input id="email" type="email" name="email" class="fp-input"
                   value="{{ old('email', $request->email) }}" autocomplete="email" required>

            <label for="password">New password</label>
            <input id="password" type="password" name="password" class="fp-input"
                   autocomplete="new-password" autofocus required>

            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="fp-input"
                   autocomplete="new-password" required>

            <button type="submit" class="fp-submit">
                <i class="fa-solid fa-check"></i> Reset password
            </button>
        </form>

        <a href="{{ route('login') }}" class="fp-back">Back to login</a>
    </div>
</div>
@endsection
