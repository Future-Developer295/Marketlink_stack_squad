@extends('Dashboard.Farmer._master')
@section('nav_stock')
active
@endsection
@section('page_title', 'Add Stock Schedule')
@section('body')
    <div class="panel" style="max-width:1440px">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-calendar2-week-fill"></i> Add Stock Schedule</span>
            <a class="btn-ghost sm" href="{{ route('farmer.stock.index') }}"><i class="bi bi-x"></i> Cancel</a>
        </div>
        <div style="padding:22px">
          <form action="{{ route('farmer.stock.store') }}" method="post">
            @csrf
          <div class="grid-2">
            <div class="field">
              <label class="field-label">Product *</label>
              <select required name="product_id" class="field-input">
                <option disabled selected hidden>Select Product</option>
                <option value="1">Dummy Product</option>
              </select>
            </div>

            <div class="field">
              <label class="field-label">Day of Week *</label>
              <select required name="day_of_week" class="field-input">
                <option disabled selected hidden>Select Day of Week</option>
                <option value="Mon">Mon</option>
                <option value="Tue">Tue</option>
                <option value="Wed">Wed</option>
                <option value="Thu">Thu</option>
                <option value="Fri">Fri</option>
                <option value="Sat">Sat</option>
                <option value="Sun">Sun</option>
              </select>
            </div>
          </div>

          <div class="grid-2">
            <div class="field">
              <label class="field-label">Quantity *</label>
              <input type="number" name="quantity" required min="0" class="field-input" placeholder="0" />
            </div>

            <div class="field">
              <label class="field-label">Unit *</label>
              <input type="text" name="unit" required class="field-input" placeholder="kg / dozen" />
            </div>
          </div>

          <div class="grid-2">
            <div class="field">
              <label class="field-label">Start Date *</label>
              <input type="date" name="start_date" required class="field-input" placeholder="" />
            </div>

            <div class="field">
              <label class="field-label">End Date</label>
              <input type="date" name="end_date"  class="field-input" placeholder="" />
            </div>
          </div>

            <div class="field">
              <label class="field-label">Status</label><br>
              <input type="checkbox" name="is_active" id="is_active" style="width:18px;height:18px;vertical-align:middle">
              <label for="is_active" style="vertical-align:middle; margin-left:6px; color:var(--muted); font-size:13px">Schedule is active</label>
            </div>

            <div class="spacer-16"></div>
            <div style="display:flex; gap:10px">
              <button class="btn-primary" type="submit"><i class="bi bi-check2"></i> Save Schedule</button>
              <a class="btn-ghost" href="{{ route('farmer.stock.index') }}">Cancel</a>
            </div>
          </form>
        </div>
    </div>
@endsection
