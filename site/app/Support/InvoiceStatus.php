<?php

namespace App\Support;

final class InvoiceStatus
{
    public const DRAFT = 'draft';

    public const SENT = 'sent';

    public const PAID = 'paid';

    public const OVERDUE = 'overdue';

    public const CANCELLED = 'cancelled';

    /** @return list<string> */
    public static function all(): array
    {
        return [
            self::DRAFT,
            self::SENT,
            self::PAID,
            self::OVERDUE,
            self::CANCELLED,
        ];
    }

    /** @return list<string> */
    public static function postSend(): array
    {
        return [self::SENT, self::PAID, self::OVERDUE, self::CANCELLED];
    }
}
