@extends('Dashboard._master')

@section('nav_reports')
active
@endsection

@section('page_title', 'Reports')

@section('body')

<div class="panel" style="max-width:1440px">
    <div class="panel-header">
        <span class="panel-title">
            <i class="bi bi-bar-chart-line-fill"></i> Generate Report
        </span>
    </div>


<div style="padding:22px">
    <form action="{{ route('report_generate') }}" method="post">
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
                <input type="date" name="date_from" class="field-input" required>
            </div>

            <div class="field">
                <label class="field-label">Date To</label>
                <input type="date" name="date_to" class="field-input" required>
            </div>

        </div>

        <button class="btn-primary" type="submit">
            <i class="bi bi-file-earmark-bar-graph"></i>
            Generate Report
        </button>

    </form>
</div>


</div>

<div class="spacer-24"></div>

<div class="panel">


<div class="panel-header" style="display:flex; justify-content:space-between; align-items:center;">

    <span class="panel-title">
        <i class="bi bi-folder2-open"></i> Generated Reports
    </span>

    <form action="{{ route('reports') }}" method="GET" style="margin:0">

        <select name="report_type"
                class="field-input"
                onchange="this.form.submit()"
                style="width:220px;">

            <option value="all"
                {{ !request('report_type') || request('report_type') == 'all' ? 'selected' : '' }}>
                All Reports
            </option>

            <option value="sales"
                {{ request('report_type') == 'sales' ? 'selected' : '' }}>
                Sales Summary
            </option>

            <option value="orders"
                {{ request('report_type') == 'orders' ? 'selected' : '' }}>
                Orders
            </option>

            <option value="farmers"
                {{ request('report_type') == 'farmers' ? 'selected' : '' }}>
                Farmer Activity
            </option>

            <option value="products"
                {{ request('report_type') == 'products' ? 'selected' : '' }}>
                Product Inventory
            </option>

        </select>

    </form>

</div>

<div class="tbl-wrap">

    <table class="dtable">

        <thead>
            <tr>
                <th>#ID</th>
                <th>Type</th>
                <th>Date Range</th>
                <th>Generated At</th>
                <th>File</th>
            </tr>
        </thead>

        <tbody>

            @forelse($reports as $report)

            <tr>

                <td>
                    <span class="id-chip">
                        {{ $report->id }}
                    </span>
                </td>

                <td>
                    <span class="badge-cat">
                        {{ ucfirst($report->report_type) }}
                    </span>
                </td>

                <td>
                    {{ $report->date_from->format('d M Y') }}
                    –
                    {{ $report->date_to->format('d M Y') }}
                </td>

                <td>
                    {{ $report->generated_at->format('Y-m-d') }}
                </td>

                <td>
                    <a class="btn-ghost sm"
                       href="{{ route('report_download', $report->id) }}">

                        <i class="bi bi-download"></i>
                        Download

                    </a>
                </td>

            </tr>

            @empty

            <tr>
                <td colspan="5" style="text-align:center; padding:30px;">
                    No reports found.
                </td>
            </tr>

            @endforelse

        </tbody>

    </table>

</div>


</div>



@endsection
