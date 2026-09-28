@extends('Dashboard.Admin._master')
@section('nav_users')
active
@endsection
@section('page_title', 'Add User')
@section('body')
    <div class="panel" style="max-width:1440px">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-people-fill"></i> Add User</span>
            <a class="btn-ghost sm" href="{{ route('admin.users.index') }}"><i class="bi bi-x"></i> Cancel</a>
        </div>
        <div style="padding:22px">
          <form action="{{ route('admin.users.store') }}" method="post">
            @csrf
          <div class="grid-2">
            <div class="field">
              <label class="field-label">Full Name *</label>
              <input type="text" name="name" required class="field-input" placeholder="Jane Doe" />
            </div>

            <div class="field">
              <label class="field-label">Email *</label>
              <input type="email" name="email" required class="field-input" placeholder="jane@example.com" />
            </div>
          </div>

          <div class="grid-2">
            <div class="field">
              <label class="field-label">Phone</label>
              <input type="tel" name="phone"  class="field-input" placeholder="0300-0000000" />
            </div>

            <div class="field">
              <label class="field-label">Role *</label>
              <select required name="role" class="field-input">
                <option disabled selected hidden>Select Role</option>
                <option value="customer">Customer</option>
                <option value="farmer">Farmer</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>

            <div class="field">
              <label class="field-label">Address</label>
              <input type="text" name="address"  class="field-input" placeholder="Street, City" />
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
              <a class="btn-ghost" href="{{ route('admin.users.index') }}">Cancel</a>
            </div>
          </form>
        </div>
    </div>
@endsection
