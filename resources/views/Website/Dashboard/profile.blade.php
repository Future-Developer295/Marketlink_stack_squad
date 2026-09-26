@extends('Website._master')

@section('page_title', 'Profile Settings')

@section('body')

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <a href="{{ url('/dashboard') }}">Dashboard</a> / <span class="active">Profile Settings</span></nav>
</div>

<section class="ml-section pt-2">
    <div class="ml-container">
        @include('Website.Partials.alerts')

        <div class="row g-4">
            <div class="col-lg-3">
                @include('Website.Dashboard._sidebar')
            </div>

            <div class="col-lg-9">
                <form method="POST" action="{{ url('/dashboard/profile') }}">
                    @csrf
                    @method('PUT')

                    <div class="ml-card mb-4">
                        <strong>Personal Information</strong>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="ml-form-label">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="ml-form-label">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="ml-form-label">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control @error('phone') is-invalid @enderror" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="ml-form-label">Address</label>
                                <input type="text" name="address" value="{{ old('address', $user->address) }}" class="form-control @error('address') is-invalid @enderror">
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="ml-card mb-4">
                        <strong>Change Password</strong>
                        <p class="small text-muted mb-2">Leave blank if you do not want to change your password.</p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="ml-form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                                @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="ml-form-label">New Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="ml-form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn ml-btn-primary"><i class="fa-solid fa-check"></i> Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
