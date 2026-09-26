
@extends('Dashboard._master')

@section('nav_stock_add')
active
@endsection

@section('page_title', 'Add Stock Schedule')

@section('body')

<div class="panel" style="max-width:1440px">

    <div class="panel-header">

        <span class="panel-title">
            <i class="bi bi-calendar2-week-fill"></i>
            Add Stock Schedule
        </span>

        <a class="btn-ghost sm" href="{{ route('stock') }}">
            <i class="bi bi-x"></i>
            Cancel
        </a>

    </div>

    <div style="padding:22px">

        <form action="{{ route('stock_store') }}" method="POST">

            @csrf

            <div class="grid-2">

                <div class="field">

                    <label class="field-label">
                        Product *
                    </label>

                    <select required name="product_id" class="field-input">

                        <option value="" disabled hidden
                            {{ old('product_id') ? '' : 'selected' }}>
                            Select Product
                        </option>

                        @foreach ($products as $product)

                            <option value="{{ $product->id }}"
                                {{ old('product_id') == $product->id ? 'selected' : '' }}>

                                {{ $product->name }}

                            </option>

                        @endforeach

                    </select>

                    @error('product_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>


                <div class="field">

                    <label class="field-label">
                        Day of Week *
                    </label>

                    <select required name="day_of_week" class="field-input">

                        <option value="" disabled hidden
                            {{ old('day_of_week') ? '' : 'selected' }}>
                            Select Day of Week
                        </option>

                        <option value="Mon"
                            {{ old('day_of_week') == 'Mon' ? 'selected' : '' }}>
                            Mon
                        </option>

                        <option value="Tue"
                            {{ old('day_of_week') == 'Tue' ? 'selected' : '' }}>
                            Tue
                        </option>

                        <option value="Wed"
                            {{ old('day_of_week') == 'Wed' ? 'selected' : '' }}>
                            Wed
                        </option>

                        <option value="Thu"
                            {{ old('day_of_week') == 'Thu' ? 'selected' : '' }}>
                            Thu
                        </option>

                        <option value="Fri"
                            {{ old('day_of_week') == 'Fri' ? 'selected' : '' }}>
                            Fri
                        </option>

                        <option value="Sat"
                            {{ old('day_of_week') == 'Sat' ? 'selected' : '' }}>
                            Sat
                        </option>

                        <option value="Sun"
                            {{ old('day_of_week') == 'Sun' ? 'selected' : '' }}>
                            Sun
                        </option>

                    </select>

                    @error('day_of_week')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>

            </div>


            <div class="grid-2">

                <div class="field">

                    <label class="field-label">
                        Quantity *
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        required
                        min="0"
                        class="field-input"
                        placeholder="0"
                        value="{{ old('quantity') }}"
                    />

                    @error('quantity')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>


                <div class="field">

                    <label class="field-label">
                        Unit *
                    </label>

                    <input
                        type="text"
                        name="unit"
                        required
                        class="field-input"
                        placeholder="kg / dozen"
                        value="{{ old('unit') }}"
                    />

                    @error('unit')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>

            </div>


            <div class="grid-2">

                <div class="field">

                    <label class="field-label">
                        Start Date *
                    </label>

                    <input
                        type="date"
                        name="start_date"
                        required
                        class="field-input"
                        value="{{ old('start_date') }}"
                    />

                    @error('start_date')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>


                <div class="field">

                    <label class="field-label">
                        End Date
                    </label>

                    <input
                        type="date"
                        name="end_date"
                        class="field-input"
                        value="{{ old('end_date') }}"
                    />

                    @error('end_date')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>

            </div>


            <div class="field">

                <label class="field-label">
                    Status
                </label>

                <br>

                <input
                    type="checkbox"
                    name="is_active"
                    id="is_active"
                    value="1"
                    style="width:18px;height:18px;vertical-align:middle"
                    {{ old('is_active', true) ? 'checked' : '' }}
                >

                <label
                    for="is_active"
                    style="vertical-align:middle; margin-left:6px; color:var(--muted); font-size:13px"
                >
                    Schedule is active
                </label>

            </div>


            <div class="spacer-16"></div>


            <div style="display:flex; gap:10px">

                <button class="btn-primary" type="submit">

                    <i class="bi bi-check2"></i>
                    Save Schedule

                </button>

                <a class="btn-ghost" href="{{ route('stock') }}">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection
