<p>Hello{{ $invoice->client->contact_name ? ' '.$invoice->client->contact_name : '' }},</p>

<p>Please find attached your {{ $invoice->type === 'invoice' ? 'invoice' : 'quotation' }}
    <strong>{{ $invoice->number }}</strong> from Enovak.</p>

<p>If you have any questions, reply to this email.</p>

<p>Thank you,<br>Enovak</p>
