@extends('Dashboard.Admin._master')
@section('nav_farmers')
active
@endsection
@section('page_title', 'Edit Farmer Profile')
@section('body')
    <div class="panel" style="max-width:1440px">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-person-workspace"></i> Farmer Profile #1</span>
            <a class="btn-ghost sm" href="{{ route('admin.farmers.index') }}"><i class="bi bi-x"></i> Back</a>
        </div>
        <div style="padding:22px">
          <form action="{{ route('admin.farmers.update', 1) }}" method="post">
            @csrf
            <div class="grid-2">
              <div class="field">
                <label class="field-label">Stall Name</label>
                <input type="text" name="stall_name" value="Dummy Stall" class="field-input" />
              </div>
              <div class="field">
                <label class="field-label">Business Name</label>
                <input type="text" name="business_name" value="" class="field-input" />
              </div>
            </div>
            <div class="field">
              <label class="field-label">Description</label>
              <textarea name="description" class="field-input"></textarea>
            </div>
            <div class="grid-3">
              <div class="field">
                <label class="field-label">City</label>
                <input type="text" name="city" value="Karachi" class="field-input" />
              </div>
              <div class="field">
                <label class="field-label">State</label>
                <input type="text" name="state" value="" class="field-input" />
              </div>
              <div class="field">
                <label class="field-label">Country</label>
                <input type="text" name="country" value="Pakistan" class="field-input" />
              </div>
            </div>
            <div class="field">
              <label class="field-label">Approval Status</label>
              <select name="approval_status" class="field-input">
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
              </select>
            </div>
            <div class="spacer-16"></div>
            <div style="display:flex; gap:10px">
              <button class="btn-primary" type="submit"><i class="bi bi-check2"></i> Save Changes</button>
              <a class="btn-ghost" href="{{ route('admin.farmers.index') }}">Cancel</a>
            </div>
          </form>
        </div>
    </div>
@endsection
