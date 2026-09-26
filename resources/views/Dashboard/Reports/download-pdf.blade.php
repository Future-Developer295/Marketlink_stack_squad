<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <title>MarketLink Report #{{ $report->id }}</title>


    <style>
        @page {
            margin: 25px 30px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #263238;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.5;
            background: #ffffff;
        }

        .top-line {
            height: 5px;
            background: #2f855a;
            margin-bottom: 25px;
        }

        .header {
            width: 100%;
            margin-bottom: 25px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .brand {
            width: 65%;
            vertical-align: top;
        }

        .brand-name {
            font-size: 25px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .brand-name span {
            color: #2f855a;
        }

        .brand-subtitle {
            color: #6b7280;
            font-size: 11px;
        }

        .report-number {
            width: 35%;
            text-align: right;
            vertical-align: top;
        }

        .report-label {
            display: block;
            color: #6b7280;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }

        .report-id {
            color: #2f855a;
            font-size: 18px;
            font-weight: bold;
        }

        .divider {
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin: 0 0 12px;
        }

        .info-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin-left: -8px;
            margin-right: -8px;
            margin-bottom: 18px;
        }

        .info-box {
            width: 25%;
            background: #f8faf9;
            border: 1px solid #e2e8e4;
            border-radius: 7px;
            padding: 12px;
            vertical-align: top;
        }

        .info-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: #7b8794;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 12px;
            font-weight: bold;
            color: #263238;
        }

        .type-badge {
            display: inline-block;
            background: #e8f5ee;
            color: #23754d;
            border: 1px solid #bfe3cf;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .summary-box {
            background: #f0fdf4;
            border: 1px solid #ccebd8;
            border-left: 4px solid #2f855a;
            padding: 13px 15px;
            margin: 5px 0 22px;
            border-radius: 5px;
        }

        .summary-title {
            font-size: 12px;
            font-weight: bold;
            color: #22633f;
            margin-bottom: 4px;
        }

        .summary-text {
            font-size: 11px;
            color: #4b5563;
        }

        .details-title {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .content-table th {
            background: #2f855a;
            color: #ffffff;
            padding: 10px 9px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .4px;
            border: 1px solid #2f855a;
        }

        .content-table td {
            padding: 10px 9px;
            border: 1px solid #e1e5e8;
            font-size: 11px;
            color: #374151;
        }

        .content-table tbody tr:nth-child(even) td {
            background: #f8faf9;
        }

        .number-cell {
            width: 8%;
            text-align: center !important;
            color: #2f855a !important;
            font-weight: bold;
        }

        .field-cell {
            width: 35%;
            font-weight: bold;
            color: #374151 !important;
        }

        .status-success {
            color: #23754d;
            font-weight: bold;
        }

        .status-dot {
            display: inline-block;
            width: 7px;
            height: 7px;
            background: #2f855a;
            border-radius: 50%;
            margin-right: 5px;
        }

        .footer {
            margin-top: 35px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-left {
            text-align: left;
            color: #9ca3af;
            font-size: 9px;
        }

        .footer-right {
            text-align: right;
            color: #9ca3af;
            font-size: 9px;
        }

        .generated {
            color: #6b7280;
            font-size: 10px;
        }
    </style>


</head>

<body>


    <div class="top-line"></div>

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="brand">
                    <div class="brand-name">
                        Market<span>Link</span>
                    </div>

                    <div class="brand-subtitle">
                        Agricultural Marketplace Management System
                    </div>
                </td>

                <td class="report-number">
                    <span class="report-label">Report Number</span>
                    <div class="report-id">
                        #{{ $report->id }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <div class="section-title">
        Report Information
    </div>

    <table class="info-table">
        <tr>

            <td class="info-box">
                <div class="info-label">Report Type</div>

                <div class="info-value">
                    <span class="type-badge">
                        {{ $report->report_type }}
                    </span>
                </div>
            </td>

            <td class="info-box">
                <div class="info-label">Date From</div>

                <div class="info-value">
                    {{ $report->date_from
                    ? \Carbon\Carbon::parse($report->date_from)->format('d M Y')
                    : 'N/A'
                }}
                </div>
            </td>

            <td class="info-box">
                <div class="info-label">Date To</div>

                <div class="info-value">
                    {{ $report->date_to
                    ? \Carbon\Carbon::parse($report->date_to)->format('d M Y')
                    : 'N/A'
                }}
                </div>
            </td>

            <td class="info-box">
                <div class="info-label">Generated</div>

                <div class="info-value">
                    {{ $report->generated_at
                    ? \Carbon\Carbon::parse($report->generated_at)->format('d M Y')
                    : now()->format('d M Y')
                }}
                </div>
            </td>

        </tr>
    </table>

    <div class="summary-box">

        <div class="summary-title">
            Report Summary
        </div>

        <div class="summary-text">
            This report contains the compiled activity data for the selected
            {{ ucfirst($report->report_type) }} category within the specified
            date range.
        </div>

    </div>

    <div class="details-title">
        Report Details
    </div>

    <table class="content-table">

        <thead>
            <tr>
                <th class="number-cell">#</th>
                <th>Information Field</th>
                <th>Details / Status</th>
            </tr>
        </thead>

        <tbody>

            <tr>
                <td class="number-cell">01</td>

                <td class="field-cell">
                    Report Category
                </td>

                <td>
                    {{ ucfirst($report->report_type) }} Activity Data
                </td>
            </tr>

            <tr>
                <td class="number-cell">02</td>

                <td class="field-cell">
                    Report Period
                </td>

                <td>
                    {{ $report->date_from
                    ? \Carbon\Carbon::parse($report->date_from)->format('d M Y')
                    : 'N/A'
                }}

                    &nbsp; — &nbsp;

                    {{ $report->date_to
                    ? \Carbon\Carbon::parse($report->date_to)->format('d M Y')
                    : 'N/A'
                }}
                </td>
            </tr>

            <tr>
                <td class="number-cell">03</td>

                <td class="field-cell">
                    Report Status
                </td>

                <td class="status-success">
                    <span class="status-dot"></span>
                    Successfully Compiled & Verified
                </td>
            </tr>

            <tr>
                <td class="number-cell">04</td>

                <td class="field-cell">
                    Generated At
                </td>

                <td class="generated">
                    {{ $report->generated_at
                    ? \Carbon\Carbon::parse($report->generated_at)->format('d M Y, h:i A')
                    : now()->format('d M Y, h:i A')
                }}
                </td>
            </tr>

        </tbody>

    </table>

    <div class="footer">

        <table class="footer-table">

            <tr>
                <td class="footer-left">
                    MarketLink &copy; {{ date('Y') }}
                </td>

                <td class="footer-right">
                    Official System Generated Report
                </td>
            </tr>

        </table>

    </div>


</body>

</html>