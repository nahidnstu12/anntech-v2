<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .header { margin-bottom: 24px; }
        .brand { font-size: 20px; font-weight: bold; }
        .muted { color: #555; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 16px; }
        table.items th, table.items td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        table.items th { background: #f3f4f6; }
        .totals { margin-top: 16px; width: 100%; }
        .totals td { padding: 4px 8px; }
        .right { text-align: right; }
        .title { font-size: 18px; font-weight: bold; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">{{ $company['name'] }}</div>
        <div class="muted">{{ $company['address'] }}</div>
        <div class="muted">{{ $company['phone'] }} · {{ $company['email'] }}</div>
    </div>

    <div class="title">{{ $invoice->type === 'invoice' ? 'Invoice' : 'Quotation' }} {{ $displayNumber }}</div>
    <p class="muted">Issue date: {{ $invoice->issue_date?->format('d M Y') ?? now()->format('d M Y') }}</p>
    @if($invoice->due_date)
        <p class="muted">Due date: {{ $invoice->due_date->format('d M Y') }}</p>
    @endif

    <p><strong>Bill to</strong><br>
        {{ $invoice->client->company_name }}<br>
        @if($invoice->client->contact_name){{ $invoice->client->contact_name }}<br>@endif
        {{ $invoice->client->email }}<br>
        @if($invoice->client->phone){{ $invoice->client->phone }}<br>@endif
        @if($invoice->client->address){{ $invoice->client->address }}@endif
    </p>

    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th class="right">Qty</th>
                <th class="right">Unit price</th>
                <th class="right">Line total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->lineItems as $line)
                <tr>
                    <td>{{ $line->description }}</td>
                    <td class="right">{{ $line->quantity }}</td>
                    <td class="right">{{ number_format((float) $line->unit_price, 2) }}</td>
                    <td class="right">{{ number_format((float) $line->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td class="right">Subtotal ({{ $invoice->currency }})</td>
            <td class="right" width="120">{{ number_format((float) $invoice->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td class="right">Tax ({{ $invoice->tax_rate }}%)</td>
            <td class="right">{{ number_format((float) $invoice->tax_amount, 2) }}</td>
        </tr>
        <tr>
            <td class="right"><strong>Total</strong></td>
            <td class="right"><strong>{{ number_format((float) $invoice->total, 2) }}</strong></td>
        </tr>
    </table>

    @if($invoice->notes_public)
        <p style="margin-top: 20px;"><strong>Notes</strong><br>{!! nl2br(e($invoice->notes_public)) !!}</p>
    @endif
</body>
</html>
