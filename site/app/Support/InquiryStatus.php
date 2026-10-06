<?php

namespace App\Support;

final class InquiryStatus
{
    public const NEW = 'new';

    public const IN_PROGRESS = 'in_progress';

    public const CLOSED = 'closed';

    public const SPAM = 'spam';

    /** @return list<string> */
    public static function all(): array
    {
        return [
            self::NEW,
            self::IN_PROGRESS,
            self::CLOSED,
            self::SPAM,
        ];
    }
}
