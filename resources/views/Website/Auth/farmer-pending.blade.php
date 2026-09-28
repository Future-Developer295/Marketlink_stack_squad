@extends('Website._master')

@section('page_title', 'Account Pending')

@section('body')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">

            <div class="card border-0 shadow-sm rounded-4 text-center p-5">

                <div class="mb-4">
                    <i class="fa-solid fa-clock"
                       style="font-size:60px; color:#198754;"></i>
                </div>

                <h2 class="fw-bold mb-3">
                    Account Pending Approval
                </h2>

                <p class="text-muted mb-4">
                    Your Farmer account is currently pending approval.
                    Please wait while the admin reviews and activates your account.
                </p>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-success px-4">
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </div>
</div>

@endsection