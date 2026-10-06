<?php

namespace App\Services;

use App\Models\InvoiceSequence;
use Illuminate\Support\Facades\DB;

class InvoiceNumberService
{
    public function assignNextNumber(int $year): string
    {
        return DB::transaction(function () use ($year) {
            $sequence = InvoiceSequence::query()
                ->where('year', $year)
                ->lockForUpdate()
                ->first();

            if (! $sequence) {
                $sequence = InvoiceSequence::create([
                    'year' => $year,
                    'last_number' => 0,
                ]);
                $sequence = InvoiceSequence::query()->whereKey($sequence->id)->lockForUpdate()->first();
            }

            $sequence->last_number = (int) $sequence->last_number + 1;
            $sequence->save();

            return sprintf('ENV-%d-%04d', $year, $sequence->last_number);
        });
    }
}
