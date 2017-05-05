<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class UserIsStaff
{
    /**
     * Check if the user belongs to the staff
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(! Auth::user()->isTrainer) return redirect('/home');
        return $next($request);

    }
}
