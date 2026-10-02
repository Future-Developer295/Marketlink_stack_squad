@extends('Dashboard._master')

@section('nav_farmers')
active
@endsection

@section('page_title', 'Edit Farmer')

@section('body')

<div class="panel" style="max-width:1440px">

    <div class="panel-header">
        <span class="panel-title">
            <i class="bi bi-person-workspace"></i>
            Edit Farmer #{{ $farmer->id }}
        </span>

        <a class="btn-ghost sm" href="{{ route('farmers') }}">
            <i class="bi bi-x"></i> Back
        </a>
    </div>

    <div style="padding:22px">

        <form action="{{ route('farmer_update', $farmer->id) }}" method="post">

            @csrf

            <div class="grid-2">

                <div class="field">
                    <label class="field-label">Market</label>

                    <select name="market_id" class="field-input">
                        @foreach($markets as $market)
                            <option value="{{ $market->id }}"
                                {{ $farmer->market_id == $market->id ? 'selected' : '' }}>
                                {{ $market->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="field">
                    <label class="field-label">Farmer</label>

                    <select name="farmer_id" class="field-input">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ $farmer->farmer_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>


            <div class="field">
                <label class="field-label">Status</label>

                <select name="is_active" class="field-input">

                    <option value="1"
                        {{ $farmer->is_active ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0"
                        {{ !$farmer->is_active ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>
            </div>


            <div class="spacer-16"></div>

            <div style="display:flex; gap:10px">

                <button class="btn-primary" type="submit">
                    <i class="bi bi-check2"></i>
                    Save Changes
                </button>

                <a class="btn-ghost" href="{{ route('farmers') }}">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection
