@extends('Dashboard._master')
@section('nav_users_add')
active
@endsection
@section('page_title', 'Add User')
@section('body')
    <div class="panel" style="max-width:1440px">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-people-fill"></i> Add User</span>
            <a class="btn-ghost sm" href="{{ route('users') }}"><i class="bi bi-x"></i> Cancel</a>
        </div>
        <div style="padding:22px">
          <form action="{{ route('user_store') }}" method="post">
            @csrf
            @if ($errors->any())
              <div style="background:#fee2e2; color:#991b1b; padding:10px 14px; border-radius:8px; margin-bottom:14px; font-size:13px">
                <ul style="margin:0; padding-left:18px">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
          <div class="grid-2">
            <div class="field">
              <label class="field-label">Full Name *</label>
              <input type="text" name="name" required class="field-input" placeholder="Jane Doe" value="{{ old('name') }}" />
            </div>

            <div class="field">
              <label class="field-label">Email *</label>
              <input type="email" name="email" required class="field-input" placeholder="jane@example.com" value="{{ old('email') }}" />
            </div>
          </div>

          <div class="grid-2">
            <div class="field">
              <label class="field-label">Phone</label>
              <input type="tel" name="phone"  class="field-input" placeholder="0300-0000000" value="{{ old('phone') }}" />
            </div>

            <div class="field">
              <label class="field-label">Role *</label>
              <select required name="role" class="field-input">
                <option disabled {{ old('role') ? '' : 'selected' }} hidden>Select Role</option>
                <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="farmer" {{ old('role') == 'farmer' ? 'selected' : '' }}>Farmer</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
              </select>
            </div>
          </div>

            <div class="field">
              <label class="field-label">Address *</label>
              <input type="text" name="address" required class="field-input" placeholder="Street, City" value="{{ old('address') }}" />
            </div>

          <div class="grid-2">
            <div class="field">
              <label class="field-label">Password *</label>
              <input type="text" name="password" required class="field-input" placeholder="Set a temporary password" />
            </div>

            <div class="field">
              <label class="field-label">Status</label><br>
              <input type="checkbox" name="is_active" id="is_active" style="width:18px;height:18px;vertical-align:middle">
              <label for="is_active" style="vertical-align:middle; margin-left:6px; color:var(--muted); font-size:13px">Account is active</label>
            </div>
          </div>

            <div class="spacer-16"></div>
            <div style="display:flex; gap:10px">
              <button class="btn-primary" type="submit"><i class="bi bi-check2"></i> Save User</button>
              <a class="btn-ghost" href="{{ route('users') }}">Cancel</a>
            </div>
          </form>
        </div>
    </div>
@endsection
