@extends('Dashboard._master')

@section('nav_reports')
    active
@endsection

@section('page_title', 'Reports')

@section('body')

    <div class="panel" style="max-width:1440px">

        <div class="panel-header">
            <span class="panel-title">
                <i class="bi bi-bar-chart-line-fill"></i>
                Generate Report
            </span>
        </div>

        <div style="padding:22px">

            <form action="{{ route('report_generate') }}" method="POST">
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

        <div class="panel-header"
            style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">

            <span class="panel-title">
                <i class="bi bi-folder2-open"></i>
                Generated Reports
            </span>

            <div style="display:flex; align-items:flex-end; gap:10px; flex-wrap:wrap;">

                <form action="{{ route('reports') }}" method="GET"
                    style="margin:0; display:flex; align-items:flex-end; gap:10px; flex-wrap:wrap;">

                    <div class="field" style="margin:0;">

                        <label class="field-label">Type</label>

                        <select name="report_type" class="field-input" style="width:180px;">

                            <option value="all"
                                {{ !request('report_type') || request('report_type') === 'all' ? 'selected' : '' }}>
                                All Reports
                            </option>

                            <option value="sales" {{ request('report_type') === 'sales' ? 'selected' : '' }}>
                                Sales Summary
                            </option>

                            <option value="orders" {{ request('report_type') === 'orders' ? 'selected' : '' }}>
                                Orders
                            </option>

                            <option value="farmers" {{ request('report_type') === 'farmers' ? 'selected' : '' }}>
                                Farmer Activity
                            </option>

                            <option value="products" {{ request('report_type') === 'products' ? 'selected' : '' }}>
                                Product Inventory
                            </option>

                        </select>

                    </div>


                    <div class="field" style="margin:0;">

                        <label class="field-label">Date From</label>

                        <input type="date" name="date_from" class="field-input" style="width:160px;"
                            value="{{ request('date_from') }}">

                    </div>


                    <div class="field" style="margin:0;">

                        <label class="field-label">Date To</label>

                        <input type="date" name="date_to" class="field-input" style="width:160px;"
                            value="{{ request('date_to') }}">

                    </div>


                    <button type="submit" class="btn-primary" style="height:38px;">

                        <i class="bi bi-funnel"></i>
                        Filter

                    </button>


                    @if (request('report_type') || request('date_from') || request('date_to'))
                        <a href="{{ route('reports') }}" class="btn-ghost sm"
                            style="height:38px; display:inline-flex; align-items:center;">

                            Clear

                        </a>
                    @endif

                </form>


                {{-- TOP EXPORT --}}
                <details class="export-dropdown">

                    <summary class="btn-ghost" style="height:38px;">

                        <i class="bi bi-download"></i>
                        Export
                        <i class="bi bi-chevron-down"></i>

                    </summary>


                    <div class="export-dropdown-menu">

                        <a href="{{ route('reports_export', array_merge(request()->query(), ['format' => 'pdf'])) }}">

                            <i class="bi bi-file-earmark-pdf"></i>

                            <span>
                                Export as PDF
                                <small>All data in one file</small>
                            </span>

                        </a>


                        <a href="{{ route('reports_export', array_merge(request()->query(), ['format' => 'xlsx'])) }}">

                            <i class="bi bi-file-earmark-excel"></i>

                            <span>
                                Export as Excel
                                <small>Separate table per report type</small>
                            </span>

                        </a>

                    </div>

                </details>

            </div>

        </div>


        <style>
            .export-dropdown {
                position: relative;
            }

            .export-dropdown summary {
                list-style: none;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .export-dropdown summary::-webkit-details-marker {
                display: none;
            }

            .export-dropdown-menu {
                position: absolute;
                right: 0;
                top: 44px;
                background: #ffffff;
                border: 1px solid #e1e7e3;
                border-radius: 8px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
                min-width: 240px;
                z-index: 20;
                padding: 6px;
            }

            .export-dropdown-menu a {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px 12px;
                border-radius: 6px;
                color: #263238;
                text-decoration: none;
                font-size: 13.5px;
            }

            .export-dropdown-menu a:hover {
                background: #f0f9f4;
            }

            .export-dropdown-menu small {
                display: block;
                color: #8a949d;
                font-size: 11px;
                font-weight: normal;
            }

            .row-export-dropdown {
                position: relative;
                display: inline-block;
            }

            .row-export-dropdown summary {
                list-style: none;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }

            .row-export-dropdown summary::-webkit-details-marker {
                display: none;
            }

            .row-export-dropdown .export-dropdown-menu {
                top: 32px;
                min-width: 130px;
            }
        </style>


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

                                <details class="row-export-dropdown">

                                    <summary class="btn-ghost sm">

                                        <i class="bi bi-download"></i>
                                        Download
                                        <i class="bi bi-chevron-down"></i>

                                    </summary>


                                    <div class="export-dropdown-menu">

                                        {{-- PDF --}}
                                        <a
                                            href="{{ route('report_download', [
                                                'id' => $report->id,
                                                'format' => 'pdf',
                                            ]) }}">

                                            <i class="bi bi-file-earmark-pdf"></i>

                                            <span>
                                                PDF
                                            </span>

                                        </a>


                                        {{-- EXCEL --}}
                                        <a
                                            href="{{ route('report_download', [
                                                'id' => $report->id,
                                                'format' => 'xlsx',
                                            ]) }}">

                                            <i class="bi bi-file-earmark-excel"></i>

                                            <span>
                                                Excel
                                            </span>

                                        </a>

                                    </div>

                                </details>

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
