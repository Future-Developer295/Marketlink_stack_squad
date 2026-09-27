@extends('Website._master')

@section('page_title', 'Profile Settings')

@section('body')

    <div class="ml-container">
        <nav class="ml-breadcrumb">
            <a href="{{ url('/') }}">Home</a> /
            <a href="{{ route('customer_dashboard') }}">Dashboard</a> /
            <span class="active">Profile Settings</span>
        </nav>
    </div>

    <section class="ml-section pt-2">
        <div class="ml-container">

            @include('Website.Partials.alerts')

            <div class="row g-4">

                <div class="col-lg-3">
                    @include('Website.Dashboard._sidebar')
                </div>

                <div class="col-lg-9">

                    <form action="{{ route('my_profile_update') }}" method="POST" enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        <div class="ml-card mb-4">

                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div>
                                    <strong>Profile Photo</strong>
                                    <div class="small text-muted">
                                        Upload or update your profile picture.
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-4 flex-wrap">

                                <div>
                                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" id="profilePreview"
                                        style="
                                        width: 120px;
                                        height: 120px;
                                        object-fit: cover;
                                        border-radius: 50%;
                                        border: 4px solid #ffffff;
                                        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
                                    ">
                                </div>

                                <div class="flex-grow-1" style="max-width:500px;">

                                    <label class="ml-form-label">
                                        Choose Profile Image
                                    </label>

                                    <input type="file" name="profile_photo" id="profile_photo"
                                        class="form-control @error('profile_photo') is-invalid @enderror"
                                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                                    @error('profile_photo')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div class="small text-muted mt-2">
                                        JPG, JPEG, PNG or WEBP. Maximum file size 2MB.
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="ml-card mb-4">

                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">

                                <div>
                                    <strong>Personal Information</strong>

                                    <div class="small text-muted">
                                        Update your customer account details.
                                    </div>
                                </div>

                                <span class="badge bg-light text-dark border text-capitalize">
                                    {{ $user->role }}
                                </span>

                            </div>

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label class="ml-form-label">
                                        Full Name
                                    </label>

                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                        class="form-control @error('name') is-invalid @enderror" required>

                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="col-md-6">

                                    <label class="ml-form-label">
                                        Email Address
                                    </label>

                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="form-control @error('email') is-invalid @enderror" required>

                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="col-md-6">

                                    <label class="ml-form-label">
                                        Phone Number
                                    </label>

                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                        class="form-control @error('phone') is-invalid @enderror">

                                    @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="col-md-6">

                                    <label class="ml-form-label">
                                        Address
                                    </label>

                                    <input type="text" name="address" value="{{ old('address', $user->address) }}"
                                        class="form-control @error('address') is-invalid @enderror" required>

                                    @error('address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>

                        </div>

                        <div class="ml-card mb-4">

                            <strong>Change Password</strong>

                            <p class="small text-muted mb-3">
                                Leave these fields blank if you do not want to change your password.
                            </p>

                            <div class="row g-3">

                                <div class="col-md-4">

                                    <label class="ml-form-label">
                                        Current Password
                                    </label>

                                    <input type="password" name="current_password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        autocomplete="current-password">

                                    @error('current_password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="col-md-4">

                                    <label class="ml-form-label">
                                        New Password
                                    </label>

                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        autocomplete="new-password">

                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="col-md-4">

                                    <label class="ml-form-label">
                                        Confirm Password
                                    </label>

                                    <input type="password" name="password_confirmation" class="form-control"
                                        autocomplete="new-password">

                                </div>

                            </div>

                        </div>

                        <button type="submit" class="btn ml-btn-primary">
                            <i class="fa-solid fa-check"></i>
                            Save Changes
                        </button>

                    </form>

                </div>

            </div>

        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const profileInput = document.getElementById('profile_photo');
            const profilePreview = document.getElementById('profilePreview');

            if (profileInput && profilePreview) {

                profileInput.addEventListener('change', function(event) {

                    const file = event.target.files[0];

                    if (file) {

                        const allowedTypes = [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ];

                        if (!allowedTypes.includes(file.type)) {
                            alert('Please select JPG, JPEG, PNG or WEBP image.');
                            profileInput.value = '';
                            return;
                        }

                        if (file.size > 2 * 1024 * 1024) {
                            alert('Profile image must be less than 2MB.');
                            profileInput.value = '';
                            return;
                        }

                        const imageUrl = URL.createObjectURL(file);

                        profilePreview.src = imageUrl;
                    }

                });

            }

        });
    </script>

@endsection
