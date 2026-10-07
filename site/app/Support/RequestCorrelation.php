<?php

namespace App\Support;

use Illuminate\Support\Str;

final class RequestCorrelation
{
    public const ATTRIBUTE = 'correlation_id';

    public static function id(): ?string
    {
        $request = request();

        if (! $request) {
            return null;
        }

        $existing = $request->attributes->get(self::ATTRIBUTE);
        if (is_string($existing) && $existing !== '') {
            return $existing;
        }

        $header = $request->header('X-Request-Id');
        $id = is_string($header) && $header !== '' ? $header : (string) Str::uuid();

        $request->attributes->set(self::ATTRIBUTE, $id);

        return $id;
    }
}
