<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class AdminUser
{
    public function handle($request, Closure $next)
    {
        if ( Auth::check() && Auth::user()->type == 'admin' )
        {
            return $next($request);
        }
        // Need to create an unauthorized route to redirect to
        return redirect('home');
    }
}