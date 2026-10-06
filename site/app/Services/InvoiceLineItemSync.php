<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceLineItem;

class InvoiceLineItemSync
{
    /** @param  list<array{description: string, quantity: float|string, unit_price: float|string}>  $lines */
    public static function replace(Invoice $invoice, array $lines): void
    {
        $invoice->lineItems()->delete();

        foreach (array_values($lines) as $index => $line) {
            InvoiceLineItem::create([
                'invoice_id' => $invoice->id,
                'sort_order' => $index,
                'description' => $line['description'],
                'quantity' => $line['quantity'],
                'unit_price' => $line['unit_price'],
                'line_total' => InvoiceCalculator::lineTotal($line['quantity'], $line['unit_price']),
            ]);
        }

        $invoice->load('lineItems');
        InvoiceCalculator::syncInvoiceTotals($invoice, $invoice->lineItems);
        $invoice->save();
    }
}
