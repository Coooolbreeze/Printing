<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\Tokens\TokenFactory;
use Closure;

class InjectUser
{
    /**
     *
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $request->offsetSet('user', User::findOrFail(TokenFactory::getCurrentUID()));
        } catch (\Exception $exception) {}

        return $next($request);
    }
}
