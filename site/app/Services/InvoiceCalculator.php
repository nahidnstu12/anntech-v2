<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceLineItem;
use App\Models\InvoiceSequence;
use Illuminate\Support\Collection;

class InvoiceCalculator
{
    /**
     * @param  iterable<int, array{quantity: float|string, unit_price: float|string}>  $lines
     * @return array{subtotal: string, tax_amount: string, total: string}
     */
    public static function totals(iterable $lines, float|string $taxRate): array
    {
        $subtotal = 0.0;
        foreach ($lines as $line) {
            $qty = (float) $line['quantity'];
            $price = (float) $line['unit_price'];
            $subtotal += round($qty * $price, 2);
        }

        $rate = (float) $taxRate;
        $taxAmount = round($subtotal * ($rate / 100), 2);
        $total = round($subtotal + $taxAmount, 2);

        return [
            'subtotal' => number_format($subtotal, 2, '.', ''),
            'tax_amount' => number_format($taxAmount, 2, '.', ''),
            'total' => number_format($total, 2, '.', ''),
        ];
    }

    public static function lineTotal(float|string $quantity, float|string $unitPrice): string
    {
        return number_format(round((float) $quantity * (float) $unitPrice, 2), 2, '.', '');
    }

    /** @param  Collection<int, InvoiceLineItem>|array<int, array<string, mixed>>  $lines */
    public static function syncInvoiceTotals(Invoice $invoice, Collection|array $lines): void
    {
        $payload = [];
        foreach ($lines as $line) {
            $payload[] = [
                'quantity' => $line instanceof InvoiceLineItem ? $line->quantity : $line['quantity'],
                'unit_price' => $line instanceof InvoiceLineItem ? $line->unit_price : $line['unit_price'],
            ];
        }

        $totals = self::totals($payload, $invoice->tax_rate);
        $invoice->fill($totals);
    }
}
