@extends('Dashboard.Farmer._master')
@section('nav_profile')
active
@endsection
@section('page_title', 'My Profile')
@section('body')
    <div class="panel" style="max-width:1440px">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-person-badge-fill"></i> My Farmer Profile</span>
            <span class="badge-status bs-in"><i class="bi bi-circle-fill"></i>  Pending Approval</span>
        </div>
        <div style="padding:22px">
          <form action="{{ route('farmer.profile.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid-2">
              <div class="field">
                <label class="field-label">Stall Name *</label>
                <input type="text" name="stall_name" required class="field-input" placeholder="e.g. Green Valley Stall" />
              </div>
              <div class="field">
                <label class="field-label">Business Name</label>
                <input type="text" name="business_name" class="field-input" placeholder="Registered business name" />
              </div>
            </div>
            <div class="field">
              <label class="field-label">Description</label>
              <textarea name="description" class="field-input" placeholder="Tell customers about your farm…"></textarea>
            </div>
            <div class="field">
              <label class="field-label">Address</label>
              <input type="text" name="address" class="field-input" placeholder="Street address" />
            </div>
            <div class="grid-3">
              <div class="field">
                <label class="field-label">City</label>
                <input type="text" name="city" class="field-input" placeholder="City" />
              </div>
              <div class="field">
                <label class="field-label">State</label>
                <input type="text" name="state" class="field-input" placeholder="State" />
              </div>
              <div class="field">
                <label class="field-label">Country</label>
                <input type="text" name="country" class="field-input" placeholder="Country" />
              </div>
            </div>
            <div class="grid-2">
              <div class="field">
                <label class="field-label">Latitude</label>
                <input type="number" step="0.00000001" name="lat" class="field-input" placeholder="24.8607" />
              </div>
              <div class="field">
                <label class="field-label">Longitude</label>
                <input type="number" step="0.00000001" name="lng" class="field-input" placeholder="67.0011" />
              </div>
            </div>
            <div class="grid-3">
              <div class="field">
                <label class="field-label">Operating Days</label>
                <input type="text" name="operating_days" class="field-input" placeholder="Mon,Wed,Fri" />
              </div>
              <div class="field">
                <label class="field-label">Start Time</label>
                <input type="time" name="start_time" class="field-input" />
              </div>
              <div class="field">
                <label class="field-label">End Time</label>
                <input type="time" name="end_time" class="field-input" />
              </div>
            </div>
            <div class="field">
              <label class="field-label">Stall Photo</label>
              <label for="stall_photo" class="upload-area">
                <i class="bi bi-cloud-upload up-icon"></i>
                <div class="up-text"><strong>Click or drag</strong> to upload<br><span>JPG, PNG, WEBP</span></div>
              </label>
              <input name="stall_photo" type="file" id="stall_photo" accept="image/*" hidden>
            </div>
            <div class="spacer-16"></div>
            <div style="display:flex; gap:10px">
              <button class="btn-primary" type="submit"><i class="bi bi-check2"></i> Save Profile</button>
            </div>
          </form>
        </div>
    </div>
@endsection
