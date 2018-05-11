<?php

namespace App\Http\Middleware;

use App\Services\Tokens\TokenFactory;
use Closure;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        TokenFactory::getCurrentUID();

        return $next($request);
    }
}
