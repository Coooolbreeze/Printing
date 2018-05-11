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
        if (request()->header('token') || request()->input('token'))
            $request->offsetSet('user', User::findOrFail(TokenFactory::getCurrentUID()));

        return $next($request);
    }
}
