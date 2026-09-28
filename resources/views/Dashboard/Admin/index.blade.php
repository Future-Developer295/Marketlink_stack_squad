@extends('Dashboard.Admin._master')
@section('nav_dashboard')
active
@endsection
@section('page_title', 'Admin Dashboard')
@section('body')
    <div class="stats-row">
        <div class="stat-card sc-indigo">
            <div class="stat-icon-wrap"><i class="bi bi-people-fill"></i></div>
            <div class="stat-num">0</div>
            <div class="stat-label">Total Users</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> Customers + Farmers</div>
        </div>
        <div class="stat-card sc-emerald">
            <div class="stat-icon-wrap"><i class="bi bi-person-workspace"></i></div>
            <div class="stat-num">0</div>
            <div class="stat-label">Pending Farmers</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> Awaiting approval</div>
        </div>
        <div class="stat-card sc-amber">
            <div class="stat-icon-wrap"><i class="bi bi-shop-window"></i></div>
            <div class="stat-num">0</div>
            <div class="stat-label">Active Markets</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> All regions</div>
        </div>
        <div class="stat-card sc-rose">
            <div class="stat-icon-wrap"><i class="bi bi-box-seam-fill"></i></div>
            <div class="stat-num">0</div>
            <div class="stat-label">Total Products</div>
            <div class="stat-trend down"><i class="bi bi-arrow-down-right"></i> Across all farmers</div>
        </div>
        <div class="stat-card sc-purple">
            <div class="stat-icon-wrap"><i class="bi bi-flag-fill"></i></div>
            <div class="stat-num">0</div>
            <div class="stat-label">Flagged Reviews</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> Needs moderation</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-person-workspace"></i> Pending Farmer Approvals</span>
            <a class="btn-ghost" href="{{ route('admin.farmers.index') }}">View all <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr><th>#ID</th><th>Stall Name</th><th>Owner</th><th>Submitted</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="id-chip">1</span></td>
                        <td><strong>Dummy Stall</strong></td>
                        <td>Dummy Farmer</td>
                        <td>2026-09-20</td>
                        <td><span class="badge-status bs-in"><i class="bi bi-circle-fill"></i> Pending</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
