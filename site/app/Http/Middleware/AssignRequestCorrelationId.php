<?php

namespace App\Http\Middleware;

use App\Support\RequestCorrelation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AssignRequestCorrelationId
{
    public function handle(Request $request, Closure $next): Response
    {
        $id = RequestCorrelation::id();

        $response = $next($request);

        if ($id) {
            $response->headers->set('X-Request-Id', $id);
        }

        return $response;
    }
}
