<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Persist application errors for super-admin review
    |--------------------------------------------------------------------------
    */

    'enabled' => env('ERROR_LOG_DATABASE', true),

    'max_stack_bytes' => (int) env('ERROR_LOG_MAX_STACK_BYTES', 65536),

    'request_keys' => [
        'password',
        'password_confirmation',
        'current_password',
        'token',
        '_token',
    ],

    /*
    | Exceptions that are normal client/auth flow — do not store in DB.
    */
    'ignore' => [
        Illuminate\Auth\AuthenticationException::class,
        Illuminate\Auth\Access\AuthorizationException::class,
        Illuminate\Database\Eloquent\ModelNotFoundException::class,
        Illuminate\Validation\ValidationException::class,
        Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
        Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class,
    ],

];
