@extends('Dashboard.Admin._master')
@section('nav_reports')
active
@endsection
@section('page_title', 'Reports')
@section('body')
    <div class="panel" style="max-width:1440px">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-bar-chart-line-fill"></i> Generate Report</span>
        </div>
        <div style="padding:22px">
            <form action="{{ route('admin.reports.generate') }}" method="post">
                @csrf
                <div class="grid-3">
                    <div class="field">
                        <label class="field-label">Report Type</label>
                        <select name="report_type" class="field-input">
                            <option value="sales">Sales Summary</option>
                            <option value="orders">Orders</option>
                            <option value="farmers">Farmer Activity</option>
                            <option value="products">Product Inventory</option>
                        </select>
                    </div>
                    <div class="field">
                        <label class="field-label">Date From</label>
                        <input type="date" name="date_from" class="field-input" />
                    </div>
                    <div class="field">
                        <label class="field-label">Date To</label>
                        <input type="date" name="date_to" class="field-input" />
                    </div>
                </div>
                <button class="btn-primary" type="submit"><i class="bi bi-file-earmark-bar-graph"></i> Generate Report</button>
            </form>
        </div>
    </div>

    <div class="spacer-24"></div>

    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-folder2-open"></i> Generated Reports</span>
        </div>
        <div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr><th>#ID</th><th>Type</th><th>Date Range</th><th>Generated At</th><th>File</th></tr>
                </thead>
                <tbody>
                                        <tr>
                        <td><span class="id-chip">1</span></td>
                        <td><span class="badge-cat">Sales Summary</span></td>
                        <td>01 Sep – 24 Sep 2026</td>
                        <td>2026-09-24</td>
                        <td><a class="btn-ghost sm" href="#"><i class="bi bi-download"></i> Download</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
