<?php

namespace App\Http\Middleware;

use App\Services\Tokens\TokenFactory;
use Closure;

class VerifyPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        TokenFactory::can($permission);

        return $next($request);
    }
}
