@extends('Dashboard._master')

@section('nav_products_add')
    active
@endsection

@section('page_title', 'Add Product')

@section('body')

    <div class="panel" style="max-width:1440px">


        <div class="panel-header">
            <span class="panel-title">
                <i class="bi bi-box-seam-fill"></i>
                Add Product
            </span>

            <a class="btn-ghost sm" href="{{ route('products') }}">
                <i class="bi bi-x"></i> Cancel
            </a>
        </div>

        <div style="padding:22px">

            <form action="{{ route('product_store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="grid-2">

                    <div class="field">
                        <label class="field-label">Product Name *</label>

                        <input type="text" name="name" value="{{ old('name') }}" required class="field-input"
                            placeholder="e.g. Organic Tomatoes">

                        @error('name')
                            <small style="color:red">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field-label">Category *</label>

                        <select name="category_id" class="field-input" required>
                            <option value="" disabled selected>Select Category</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('category_id')
                            <small style="color:red">{{ $message }}</small>
                        @enderror
                    </div>

                </div>


                <div class="field">

                    <label class="field-label">
                        Description
                    </label>

                    <textarea name="description" class="field-input" placeholder="Describe freshness, origin, etc." rows="4">{{ old('description') }}</textarea>

                </div>


                <div class="grid-3">

                    <div class="field">

                        <label class="field-label">
                            Price (PKR) *
                        </label>

                        <input type="number" name="price" value="{{ old('price') }}" required step="0.01"
                            min="0" class="field-input" placeholder="0.00">

                    </div>


                    <div class="field">

                        <label class="field-label">
                            Stock Quantity *
                        </label>

                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity') }}" required
                            min="0" class="field-input" placeholder="0">

                    </div>


                    <div class="field">

                        <label class="field-label">
                            Unit *
                        </label>

                        <input type="text" name="unit" value="{{ old('unit') }}" required class="field-input"
                            placeholder="kg / dozen / litre">

                    </div>

                </div>


                <div class="field">

                    <label class="field-label">
                        Availability
                    </label>

                    <br>

                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                        style="width:18px;height:18px;vertical-align:middle">

                    <label for="is_active"
                        style="vertical-align:middle;
                         margin-left:6px;
                         color:var(--muted);
                         font-size:13px">
                        Product is active / visible to customers
                    </label>

                </div>


                <div class="field">

                    <label class="field-label">
                        Product Image
                    </label>

                    <label for="image" class="upload-area">

                        <i class="bi bi-cloud-upload up-icon"></i>

                        <div class="up-text">

                            <strong>Click or drag</strong>
                            to upload

                            <br>

                            <span>
                                JPG, PNG, WEBP
                            </span>

                        </div>

                    </label>

                    <input name="image" type="file" id="image" accept="image/jpeg,image/png,image/webp" hidden>

                </div>


                <div class="spacer-16"></div>

                <div style="display:flex; gap:10px">

                    <button class="btn-primary" type="submit">
                        <i class="bi bi-check2"></i>
                        Save Product
                    </button>

                    <a class="btn-ghost" href="{{ route('products') }}">
                        Cancel
                    </a>

                </div>

            </form>

        </div>


    </div>

@endsection
