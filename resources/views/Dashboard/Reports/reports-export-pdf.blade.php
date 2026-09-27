<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <title>MarketLink Reports Export</title>

    <style>
        @page {
            margin: 22px 25px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
            color: #263238;
            margin: 0;
            padding: 0;
        }

        .top-bar {
            height: 5px;
            background: #2f855a;
            margin-bottom: 18px;
        }

        .header {
            width: 100%;
            padding-bottom: 13px;
            border-bottom: 1px solid #dfe5e1;
            margin-bottom: 14px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .brand-area {
            width: 65%;
            vertical-align: top;
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 3px;
        }

        .brand span {
            color: #2f855a;
        }

        .subtitle {
            color: #7a858e;
            font-size: 8px;
        }

        .report-area {
            width: 35%;
            text-align: right;
            vertical-align: top;
        }

        .report-label {
            color: #8a949d;
            font-size: 7px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-title {
            color: #1f2937;
            font-size: 15px;
            font-weight: bold;
            margin-top: 2px;
        }

        .report-badge {
            display: inline-block;
            background: #eaf6ef;
            border: 1px solid #c8e6d3;
            color: #26764d;
            padding: 4px 9px;
            margin-top: 5px;
            font-size: 7px;
            font-weight: bold;
        }

        .info-box {
            background: #f8faf9;
            border: 1px solid #e1e7e3;
            padding: 9px 10px;
            margin-bottom: 17px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: none;
            padding: 2px 8px;
            vertical-align: top;
        }

        .info-table td:first-child {
            padding-left: 0;
        }

        .info-label {
            color: #89939b;
            font-size: 7px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .info-value {
            color: #263238;
            font-size: 9px;
            font-weight: bold;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-header {
            width: 100%;
            margin-bottom: 7px;
        }

        .section-title {
            color: #1f2937;
            font-size: 11px;
            font-weight: bold;
        }

        .section-line {
            border-bottom: 2px solid #2f855a;
            width: 32px;
            margin-top: 4px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .data-table th {
            background: #2f855a;
            color: #ffffff;
            padding: 6px 5px;
            text-align: left;
            font-size: 7px;
            font-weight: bold;
            border: 1px solid #2f855a;
            text-transform: uppercase;
        }

        .data-table td {
            border: 1px solid #dfe4e1;
            padding: 5px;
            font-size: 7.5px;
            vertical-align: top;
            color: #374151;
        }

        .data-table tbody tr:nth-child(even) td {
            background: #f7faf8;
        }

        .data-table tbody tr:nth-child(odd) td {
            background: #ffffff;
        }

        .id {
            color: #2f855a;
            font-weight: bold;
            text-align: center;
        }

        .amount {
            color: #216e47;
            font-weight: bold;
            white-space: nowrap;
        }

        .status {
            color: #216e47;
            font-weight: bold;
        }

        .empty {
            text-align: center !important;
            padding: 14px !important;
            color: #8a949d !important;
            background: #fafafa !important;
            font-size: 8px !important;
        }

        .footer {
            margin-top: 22px;
            padding-top: 8px;
            border-top: 1px solid #dfe4e1;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-left {
            color: #8a949d;
            font-size: 7px;
            text-align: left;
        }

        .footer-right {
            color: #8a949d;
            font-size: 7px;
            text-align: right;
        }
    </style>

</head>

<body>

    <div class="top-bar"></div>

    <div class="header">

        <table class="header-table">
            <tr>

                <td class="brand-area">
                    <div class="brand">Market<span>Link</span></div>
                    <div class="subtitle">Market Management & Reporting System</div>
                </td>

                <td class="report-area">
                    <div class="report-label">Export</div>
                    <div class="report-title">Reports Data Export</div>
                    <div class="report-badge">
                        {{ $filters['report_type'] == 'all' ? 'ALL TYPES' : strtoupper($filters['report_type']) }}
                    </div>
                </td>

            </tr>
        </table>

    </div>

    <div class="info-box">

        <table class="info-table">
            <tr>

                <td width="34%">
                    <div class="info-label">Date From</div>
                    <div class="info-value">{{ $filters['date_from'] ?? 'Any' }}</div>
                </td>

                <td width="33%">
                    <div class="info-label">Date To</div>
                    <div class="info-value">{{ $filters['date_to'] ?? 'Any' }}</div>
                </td>

                <td width="33%">
                    <div class="info-label">Generated At</div>
                    <div class="info-value">{{ now()->format('d M Y H:i') }}</div>
                </td>

            </tr>
        </table>

    </div>

    @forelse ($sections as $key => $section)

    <div class="section">

        <div class="section-header">
            <div class="section-title">{{ $section['label'] }}</div>
            <div class="section-line"></div>
        </div>

        @if($key === 'orders')

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Farmer</th>
                    <th>Pickup Slot</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($section['data'] as $order)
                <tr>
                    <td class="id">#{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>{{ $order->farmer->stall_name ?? 'N/A' }}</td>
                    <td>{{ $order->pickupSlot->id ?? 'N/A' }}</td>
                    <td class="amount">{{ number_format($order->total_amount, 2) }}</td>
                    <td class="status">{{ ucfirst($order->status) }}</td>
                    <td>{{ $order->order_date->format('d M Y H:i') }}</td>
                    <td>{{ $order->notes ?? 'N/A' }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="empty">No orders found for this date range.</td></tr>
                @endforelse
            </tbody>
        </table>

        @elseif($key === 'farmers')

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Farmer</th>
                    <th>Stall Name</th>
                    <th>Business</th>
                    <th>Description</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Operating Days</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Approval</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($section['data'] as $farmer)
                <tr>
                    <td class="id">#{{ $farmer->id }}</td>
                    <td>{{ $farmer->user->name ?? 'N/A' }}</td>
                    <td>{{ $farmer->stall_name }}</td>
                    <td>{{ $farmer->business_name }}</td>
                    <td>{{ $farmer->description }}</td>
                    <td>{{ $farmer->address }}</td>
                    <td>{{ $farmer->city }}</td>
                    <td>{{ $farmer->state }}</td>
                    <td>{{ $farmer->country }}</td>
                    <td>{{ $farmer->operating_days }}</td>
                    <td>{{ $farmer->start_time }}</td>
                    <td>{{ $farmer->end_time }}</td>
                    <td class="status">{{ ucfirst($farmer->approval_status) }}</td>
                </tr>
                @empty
                <tr><td colspan="13" class="empty">No farmers found for this date range.</td></tr>
                @endforelse
            </tbody>
        </table>

        @else

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Farmer</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Unit</th>
                    <th>Active</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($section['data'] as $product)
                <tr>
                    <td class="id">#{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->farmer->stall_name ?? 'N/A' }}</td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td>{{ $product->description }}</td>
                    <td class="amount">{{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>{{ $product->unit }}</td>
                    <td class="status">{{ $product->is_active ? 'Yes' : 'No' }}</td>
                </tr>
                @empty
                <tr><td colspan="9" class="empty">No products found for this date range.</td></tr>
                @endforelse
            </tbody>
        </table>

        @endif

    </div>

    @empty

    <div class="section">
        <table class="data-table">
            <tbody>
                <tr>
                    <td class="empty">No data found for the selected filters.</td>
                </tr>
            </tbody>
        </table>
    </div>

    @endforelse

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="footer-left">MarketLink &copy; {{ date('Y') }}</td>
                <td class="footer-right">Reports Data Export &nbsp; | &nbsp; Generated {{ now()->format('d M Y H:i') }}</td>
            </tr>
        </table>
    </div>

</body>

</html>
