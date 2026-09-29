@extends('Website._master')

@section('page_title', $user->approval_status === 'rejected' ? 'Account Not Approved' : 'Account Pending')

@section('body')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">

            <div class="card border-0 shadow-sm rounded-4 text-center p-5">

                @if ($user->approval_status === 'rejected')
                    <div class="mb-4">
                        <i class="fa-solid fa-circle-xmark" style="font-size:60px; color:#dc3545;"></i>
                    </div>

                    <h2 class="fw-bold mb-3">Account Not Approved</h2>

                    <p class="text-muted mb-3">
                        Sorry, your Farmer account could not be approved this time.
                    </p>

                    @if ($user->rejection_reason)
                        <div class="alert alert-danger text-start mb-4">
                            <strong>Comment from the admin</strong><br>
                            {{ $user->rejection_reason }}
                        </div>
                    @endif

                    <p class="text-muted small mb-4">
                        If you think this is a mistake, please <a href="{{ url('/contact') }}">contact us</a>.
                    </p>
                @else
                    <div class="mb-4">
                        <i class="fa-solid fa-clock" style="font-size:60px; color:#198754;"></i>
                    </div>

                    <h2 class="fw-bold mb-3">Account Pending Approval</h2>

                    <p class="text-muted mb-4">
                        Your Farmer account is currently pending approval.
                        You will get an email as soon as the admin approves it,
                        and you can then open your dashboard.
                    </p>
                @endif

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success px-4">Logout</button>
                </form>

            </div>

        </div>
    </div>
</div>

@endsection
