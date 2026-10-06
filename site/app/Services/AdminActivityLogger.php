<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Request;
use Spatie\Activitylog\Models\Activity;

class AdminActivityLogger
{
    public static function auth(string $description, ?User $causer = null, array $extra = []): Activity
    {
        return activity('auth')
            ->causedBy($causer)
            ->withProperties(array_merge(self::requestContext(), $extra))
            ->log($description);
    }

    public static function user(string $description, User $causer, User $target, array $extra = []): Activity
    {
        return activity('user')
            ->causedBy($causer)
            ->performedOn($target)
            ->withProperties(array_merge(self::requestContext(), ['target_user_id' => $target->id], $extra))
            ->log($description);
    }

    public static function role(string $description, User $causer, array $extra = []): Activity
    {
        return activity('role')
            ->causedBy($causer)
            ->withProperties(array_merge(self::requestContext(), $extra))
            ->log($description);
    }

    /** @return array{ip: ?string, user_agent: ?string} */
    private static function requestContext(): array
    {
        return [
            'ip' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ];
    }
}
