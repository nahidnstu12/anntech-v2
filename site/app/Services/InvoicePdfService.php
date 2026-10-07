<?php

namespace App\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoicePdfService
{
    public function generate(Invoice $invoice, ?string $displayNumber = null): string
    {
        $invoice->load(['client', 'lineItems', 'billingUser']);

        $company = require resource_path('data/content.php');
        $company = $company['company'];

        $number = $displayNumber ?? $invoice->displayNumber();

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'company' => $company,
            'displayNumber' => $number,
        ])->setPaper('a4');

        $relative = $this->storagePath($invoice, $number);
        Storage::disk('local')->put($relative, $pdf->output());

        return $relative;
    }

    public function storagePath(Invoice $invoice, string $number): string
    {
        $year = $invoice->issue_date?->format('Y') ?? now()->format('Y');
        $safe = str_replace('/', '-', $number);

        return "invoices/{$year}/{$safe}.pdf";
    }

    public function stream(Invoice $invoice): \Symfony\Component\HttpFoundation\Response
    {
        if ($invoice->pdf_path && Storage::disk('local')->exists($invoice->pdf_path)) {
            return response()->file(Storage::disk('local')->path($invoice->pdf_path));
        }

        $path = $this->generate($invoice);
        $invoice->forceFill(['pdf_path' => $path])->save();

        return response()->file(Storage::disk('local')->path($path));
    }
}
