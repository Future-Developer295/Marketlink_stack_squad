@extends('Dashboard.Farmer._master')
@section('nav_products')
active
@endsection
@section('page_title', 'Edit Product')
@section('body')
    <div class="panel" style="max-width:1440px">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-box-seam-fill"></i> Edit Product</span>
            <a class="btn-ghost sm" href="{{ route('farmer.products.index') }}"><i class="bi bi-x"></i> Cancel</a>
        </div>
        <div style="padding:22px">
          <div style="color:var(--muted); font-size:13px; margin-bottom:12px"><i class="bi bi-info-circle"></i> Editing #1 — @method('put') should be added once wired to the controller.</div>
          <form action="{{ route('farmer.products.update') }}" method="post" enctype="multipart/form-data">
            @csrf
          <div class="grid-2">
            <div class="field">
              <label class="field-label">Product Name *</label>
              <input type="text" name="name" required class="field-input" placeholder="e.g. Organic Tomatoes" />
            </div>

            <div class="field">
              <label class="field-label">Category *</label>
              <select required name="category_id" class="field-input">
                <option disabled selected hidden>Select Category</option>
                <option value="1">Vegetables</option>
                <option value="2">Fruits</option>
                <option value="3">Dairy</option>
              </select>
            </div>
          </div>

            <div class="field">
              <label class="field-label">Description</label>
              <textarea  name="description" class="field-input" placeholder="Describe freshness, origin, etc."></textarea>
            </div>

          <div class="grid-3">
            <div class="field">
              <label class="field-label">Price (PKR) *</label>
              <input type="number" name="price" required step="0.01" min="0" class="field-input" placeholder="0.00" />
            </div>

            <div class="field">
              <label class="field-label">Stock Quantity *</label>
              <input type="number" name="stock_quantity" required min="0" class="field-input" placeholder="0" />
            </div>

            <div class="field">
              <label class="field-label">Unit *</label>
              <input type="text" name="unit" required class="field-input" placeholder="kg / dozen / litre" />
            </div>
          </div>

            <div class="field">
              <label class="field-label">Availability</label><br>
              <input type="checkbox" name="is_active" id="is_active" style="width:18px;height:18px;vertical-align:middle">
              <label for="is_active" style="vertical-align:middle; margin-left:6px; color:var(--muted); font-size:13px">Product is active / visible to customers</label>
            </div>

            <div class="field">
              <label class="field-label">Product Image</label>
              <label for="image" class="upload-area">
                <i class="bi bi-cloud-upload up-icon"></i>
                <div class="up-text">
                  <strong>Click or drag</strong> to upload
                  <br><span>JPG, PNG, WEBP</span>
                </div>
              </label>
              <input  name="image" type="file" id="image" accept="image/*" hidden>
            </div>

            <div class="spacer-16"></div>
            <div style="display:flex; gap:10px">
              <button class="btn-primary" type="submit"><i class="bi bi-check2"></i> Update Product</button>
              <a class="btn-ghost" href="{{ route('farmer.products.index') }}">Cancel</a>
            </div>
          </form>
        </div>
    </div>
@endsection
