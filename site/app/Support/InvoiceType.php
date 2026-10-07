<?php

namespace App\Support;

final class InvoiceType
{
    public const QUOTATION = 'quotation';

    public const INVOICE = 'invoice';

    /** @return list<string> */
    public static function all(): array
    {
        return [self::QUOTATION, self::INVOICE];
    }
}
