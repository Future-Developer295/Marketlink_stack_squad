@extends('Website.Dashboard._layout')
@section('page_title', 'Account settings')
@section('banner_title', 'A space that feels like you.')
@section('banner_text', 'Keep your details current for an easier market day.')
@section('banner_icon', 'fa-sliders')
@section('account_content')
<div class="account-settings">                <form method="POST" action="{{ route('customer_profile_update') }}">
                    @csrf
                    @method('PUT')

                    <div class="customer-panel mb-4">
                        <strong>Personal Information</strong>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="customer-field-label" for="account-name">Full Name</label><input id="account-name" type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="customer-field-label" for="account-email">Email Address</label><input id="account-email" type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="customer-field-label" for="account-phone">Phone Number</label><input id="account-phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control @error('phone') is-invalid @enderror" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="customer-field-label" for="account-address">Address</label><input id="account-address" type="text" name="address" value="{{ old('address', $user->address) }}" class="form-control @error('address') is-invalid @enderror">
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="customer-panel mb-4">
                        <strong>Change Password</strong>
                        <p class="small text-muted mb-2">Leave blank if you do not want to change your password.</p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="customer-field-label" for="account-current_password">Current Password</label><input id="account-current_password" type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                                @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="customer-field-label" for="account-password">New Password</label><input id="account-password" type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="customer-field-label" for="account-password_confirmation">Confirm Password</label><input id="account-password_confirmation" type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="customer-button"><i class="fa-solid fa-check"></i> Save Changes</button>
                </form></div>
@endsection
