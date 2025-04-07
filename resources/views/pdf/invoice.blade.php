<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .flex {
            display: flex;
        }
        .justify-between {
            justify-content: space-between;
        }
        .w-1/2 {
            width: 48%;
        }
        .text-right {
            text-align: right;
        }
        .mb-2 {
            margin-bottom: 8px;
        }
        .mb-8 {
            margin-bottom: 32px;
        }
        .font-bold {
            font-weight: bold;
        }
        .text-lg {
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .totals {
            margin-left: auto;
            width: 300px;
        }
        .totals table {
            border: none;
        }
        .totals td {
            border: none;
            padding: 4px 8px;
        }
        .totals tr:last-child td {
            font-weight: bold;
            font-size: 14px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>INVOICE</h1>
            <p>Invoice #{{ $invoice->invoice_number }}</p>
            <p>Date: {{ $invoice->issue_date->format('F d, Y') }}</p>
            <p>Due Date: {{ $invoice->due_date->format('F d, Y') }}</p>
        </div>

        <table style="width: 100%; margin-bottom: 32px;">
            <tr>
                <td style="width: 48%; vertical-align: top;">
                    <h2 style="font-size: 16px; font-weight: bold; margin-bottom: 8px;">From:</h2>
                    <p style="font-weight: bold;">{{ $invoice->company->name }}</p>
                    <p>{{ $invoice->company->address }}</p>
                    <p>{{ $invoice->company->city }}, {{ $invoice->company->state }} {{ $invoice->company->postal_code }}</p>
                    <p>{{ $invoice->company->country }}</p>
                    <p>Phone: {{ $invoice->company->phone }}</p>
                    <p>Email: {{ $invoice->company->email }}</p>
                </td>
                <td style="width: 4%;"></td>
                <td style="width: 48%; vertical-align: top; text-align: right;">
                    <h2 style="font-size: 16px; font-weight: bold; margin-bottom: 8px;">To:</h2>
                    <p style="font-weight: bold;">{{ $invoice->client->name }}</p>
                    <p>{{ $invoice->client->address }}</p>
                    <p>{{ $invoice->client->city }}, {{ $invoice->client->state }} {{ $invoice->client->postal_code }}</p>
                    <p>{{ $invoice->client->country }}</p>
                    <p>Phone: {{ $invoice->client->phone }}</p>
                    <p>Email: {{ $invoice->client->email }}</p>
                </td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td>${{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td>${{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>Tax ({{ $invoice->tax_rate }}%):</td>
                    <td>${{ number_format($invoice->tax, 2) }}</td>
                </tr>
                <tr>
                    <td>Total:</td>
                    <td>${{ number_format($invoice->total, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>Please make payment to the account specified above.</p>
            <p>This is a computer-generated document. No signature is required.</p>
        </div>
    </div>
</body>
</html> 