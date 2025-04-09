&lt;!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Specifications for Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .header-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .header-table td {
            vertical-align: middle;
            padding: 10px;
        }
        .logo-container {
            height: 50px;
            display: flex;
            align-items: center;
        }
        .logo-container img {
            max-height: 50px;
            width: auto;
        }
        .name {
            font-size: 16px;
            font-weight: bold;
            padding-left: 20px;
        }
        .specifications-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .specifications-table th, .specifications-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .specifications-table th {
            background-color: #f5f5f5;
        }
        .total {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td width="50%">
                <div style="display: flex; align-items: center;">
                    <div class="logo-container">
                        @if($company->logo)
                            <img src="{{ storage_path('app/public/' . $company->logo) }}" alt="Company Logo">
                        @endif
                    </div>
                    <div class="name">{{ $company->name }}</div>
                </div>
            </td>
            <td width="50%" style="text-align: right;">
                <div style="display: flex; align-items: center; justify-content: flex-end;">
                    <div class="logo-container">
                        @if($client->image)
                            <img src="{{ storage_path('app/public/' . $client->image) }}" alt="Client Logo">
                        @endif
                    </div>
                    <div class="name">{{ $client->name }}</div>
                </div>
            </td>
        </tr>
    </table>

    <h1>Specifications for Invoice #{{ $invoice->invoice_number }}</h1>

    <table class="specifications-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach($specifications as $specification)
                <tr>
                    <td>{{ $specification->date->format('Y-m-d') }}</td>
                    <td>{!! nl2br(e($specification->description)) !!}</td>
                    <td>{{ number_format($specification->timespent / 60, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p>Total Hours: {{ number_format($specifications->sum('timespent') / 60, 2) }}</p>
    </div>
</body>
</html> 