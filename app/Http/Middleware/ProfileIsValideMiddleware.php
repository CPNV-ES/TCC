<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ProfileIsValideMiddleware
{
    /**
     * If the user is logged and need to check his profile, redirect to /profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check() && Auth::user()->to_verify == 1)
        {
            return redirect('/profile');
        }
        return $next($request);
    }
}
