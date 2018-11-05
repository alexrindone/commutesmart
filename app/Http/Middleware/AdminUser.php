<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class AdminUser
{
    public function handle($request, Closure $next)
    {
        $valid_admin_emails = ['arugg@coastbus.org', 'arindone1679@gmail.com', 'arugg@commutesmartseacoast.org'];
        if ( Auth::check() && in_array(Auth::user()->email, $valid_admin_emails) )
        {
            return $next($request);
        }
        // Need to create an unauthorized route to redirect to
        return redirect('home');
    }
}