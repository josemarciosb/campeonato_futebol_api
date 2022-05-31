<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (auth($guard)->guest()) {
            if ($guard === 'api') {
                return response('Faça login para acessar', 401);
            } else {
                return redirect()->guest('login');
            }
        }
        return $next($request);
    }
}
