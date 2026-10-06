<?php

namespace App\Http\Middleware;

use App\Support\AdminRoles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole(AdminRoles::SUPER_ADMIN)) {
            abort(403, 'Super admin access required.');
        }

        return $next($request);
    }
}
