<!DOCTYPE html>
<html lang="{{ $invoice->language }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('invoice.invoice') }} #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }
        .container {
            padding: 20px;
        }
        .header {
            margin-bottom: 30px;
        }
        .header-table {
            padding: 0;
            margin: 0;
            width: 100%;
            border-spacing: 0;
        }
        .header-table td, .header-table th {
            vertical-align: top;
            padding: 10px;
        }
        .company-info {
            width: 60%;
        }
        .items {
            margin-bottom: 30px;
        }
        .items table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items th, .items td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .items th {
            background-color: #f5f5f5;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .logo {
            max-width: 150px;
            max-height: 100px;
            margin-bottom: 20px;
        }
        .delimiter {
            height: 10px;
        }
        .bill-to {
            color: gray;
        }
        .invoice-info-table {
            padding: 0;
            margin: 0;
            width: 100%;
            border-spacing: 0;
        }
        .invoice-info-title {
            font-size: 24px;
            font-weight: 700;
            width: 100%;
        }
        .invoice-number {
            font-size: 16px;
            color: gray;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
        <table class="header-table">
        <tr>
            <td class="company-info">
                <div><strong>{{ $company->name }}</strong><br>
                <div>{{ $company->address }}, {{ $company->postal_code }}, {{ $company->city }}</div>
                <div>{{ $company->country }}</div>
                <div>{{ __('invoice.phone') }}: {{ $company->phone }}</div>
                <div>{{ __('invoice.email') }}: {{ $company->email }}</div>
                <div>{{ __('invoice.tax_number') }}: {{ $company->tax_number }}</div>
                <div>some html</div>
                <div class="delimiter"></div>
                <div class="bill-to">{{ __('invoice.bill_to') }}</div>
                <div><strong>{{ $client->name }}</strong></div>
                <div>{{ $client->address }}, {{ $client->postal_code }}, {{ $client->country }}</div>
                <div>{{ $client->city }}</div>
                <div>{{ __('invoice.phone') }}: {{ $client->phone }}</div>
                <div>{{ __('invoice.email') }}: {{ $client->email }}</div>
                <div>{{ __('invoice.tax_number') }}: {{ $client->tax_number }}</div>
            </td>
            <td class="text-right">
                <div class="invoice-info-title">{{ __('invoice.invoice') }}</div>
                <div class="invoice-number"># {{ $invoice->invoice_number }}</div>
                <div class="delimiter"></div>
                <table class="invoice-info-table text-right">
                    <tr>
                        <th class="text-right">{{ __('invoice.issue_date') }}</th>
                        <td class="text-right">{{ $invoice->issue_date->format('d.m.Y.') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right">{{ __('invoice.due_date') }}</th>
                        <td class="text-right">{{ $invoice->due_date->format('d.m.Y.') }}</td>
                    </tr>
                    <tr style="background-color: #f5f5f5;">
                        <th class="text-right">{{ __('invoice.total') }}</th>
                        <th class="text-right">{{ number_format($total, 2) }}</th>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
        </div>

        <div class="items">
            <h3>{{ __('invoice.items') }}</h3>
            <table>
                <thead>
                    <tr>
                        <th>{{ __('invoice.description') }}</th>
                        <th>{{ __('invoice.quantity') }}</th>
                        <th>{{ __('invoice.unit_price') }}</th>
                        <th>{{ __('invoice.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item['description'] }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>{{ number_format($item['unit_price'], 2) }}</td>
                            <td>{{ number_format($item['total'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>{{ __('invoice.subtotal') }}:</strong></td>
                        <td><strong>{{ number_format($subtotal, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"><strong>{{ __('invoice.tax') }} ({{ $invoice->tax_rate }}%):</strong></td>
                        <td><strong>{{ number_format($tax, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"><strong>{{ __('invoice.total') }}:</strong></td>
                        <td><strong>{{ number_format($total, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="footer">
            <p>{{ __('invoice.thank_you') }}</p>
            <p>{{ $company->name }}</p>
        </div>
    </div>
</body>
</html> 