<?php

namespace App\Http\Middleware;

use App\Services\Tokens\TokenFactory;
use Closure;

class VerifyRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        TokenFactory::needRole($role);

        return $next($request);
    }
}
