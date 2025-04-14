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
                        <div><strong>{{ $invoice->issuer_name }}</strong><br>
                        <div>{{ $invoice->issuer_address }}</div>
                        <div>{{ $invoice->issuer_city }}, {{ $invoice->issuer_state }} {{ $invoice->issuer_zip }}</div>
                        <div>{{ $invoice->issuer_country }}</div>
                        <div>{{ __('invoice.tax_number') }}: {{ $invoice->issuer_tax_number }}</div>
                        <div class="delimiter"></div>
                        <div class="bill-to">{{ __('invoice.bill_to') }}:</div>
                        <div><strong>{{ $invoice->client_name }}</strong></div>
                        <div>{{ $invoice->client_address }}</div>
                        <div>{{ $invoice->client_city }}, {{ $invoice->client_state }} {{ $invoice->client_zip }}</div>
                        <div>{{ $invoice->client_country }}</div>
                        <div>{{ __('invoice.tax_number') }}: {{ $invoice->client_tax_number }}</div>
                    </td>
                    <td style="text-align: right;">
                        <div class="invoice-info-title">{{ __('invoice.invoice') }}</div>
                        <div class="invoice-number"># {{ $invoice->invoice_number }}</div>
                        <div class="delimiter"></div>
                        <table class="invoice-info-table" style="text-align: right;">
                            <tr>
                                <th style="text-align: right;">{{ __('invoice.issue_date') }}:</th>
                                <td style="text-align: right;">{{ $invoice->issue_date->format('d.m.Y.') }}</td>
                            </tr>
                            <tr>
                                <th style="text-align: right;">{{ __('invoice.due_date') }}:</th>
                                <td style="text-align: right;">{{ $invoice->due_date->format('d.m.Y.') }}</td>
                            </tr>
                            <tr style="background-color: #f5f5f5;">
                                <th style="text-align: right;">{{ __('invoice.total') }}:</th>
                                <th style="text-align: right;">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</th>
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
                        <th style="text-align: center;">{{ __('invoice.description') }}</th>
                        <th style="text-align: center;">{{ __('invoice.quantity') }}</th>
                        <th style="text-align: center;">{{ __('invoice.unit_price') }}</th>
                        <th style="text-align: center;">{{ __('invoice.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item['description'] }}</td>
                            <td style="text-align: center;">{{ $item['quantity'] }}</td>
                            <td style="text-align: right;">{{ number_format($item['unit_price'], 2) }}</td>
                            <td style="text-align: right;">{{ number_format($item['total'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;">{{ __('invoice.subtotal') }}:</td>
                        <td style="text-align: right;">{{ number_format($invoice->subtotal, 2) }}</td>
                    </tr>
                    @if($invoice->tax_rate > 0)
                    <tr>
                        <td colspan="3" style="text-align: right;">{{ __('invoice.tax') }} ({{ $invoice->tax_rate }}%):</td>
                        <td style="text-align: right;">{{ number_format($invoice->tax_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($invoice->discount > 0)
                    <tr>
                        <td colspan="3" style="text-align: right;">Discount ({{ $invoice->discount_rate }}%):</td>
                        <td style="text-align: right;">{{ number_format($invoice->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr style="background-color: #f5f5f5;">
                        <td colspan="3" style="text-align: right;"><strong>{{ __('invoice.total') }}:</strong></td>
                        <td style="text-align: right;"><strong>{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html> 