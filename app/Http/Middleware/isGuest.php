<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::guard('user')->check()) {
            return redirect('/');
        }

        if (Auth::guard('company')->check()) {
            return redirect('dashboard-company');
        }

        if (Auth::guard('admin')->check()) {
            return redirect('dashboard');
        }

        return $next($request);
    }
}
