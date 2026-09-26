@extends('Dashboard.Farmer._master')
@section('nav_slots')
active
@endsection
@section('page_title', 'Pickup Slots')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-clock-history"></i> Pickup Slots</span>
            <div class="panel-tools">
                <form class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Search…" />
                </form>
                <a class="btn-primary" href="{{ route('farmer.slots.create') }}"><i class="bi bi-plus-lg"></i> Add Slot</a>
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Market</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                                        <tr>
                            <td><span class='id-chip'>1</span></td>
                            <td><strong>Dummy Market</strong></td>
                            <td>2026-09-27</td>
                            <td>9:00 AM – 11:00 AM</td>
                            <td><span class='qty-tag'>0</span></td>
                            <td><span class="badge bg-success">Available</span></td>
                            <td><div class='action-wrap'><a class='btn-ghost sm' href='{{ route("farmer.slots.edit", 1) }}'><i class='bi bi-pencil'></i></a><form action='{{ route("farmer.slots.destroy", 1) }}' method='post'><button class='btn-ghost sm danger' type='submit'><i class='bi bi-trash'></i></button></form></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
