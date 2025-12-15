<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Company
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (! Auth::guard('company')->check() && ! Auth::guard('admin')->check() && ! Auth::guard('user')->check()) {
            return redirect('login');
        }

        if (Auth::guard('admin')->check()) {
            return redirect('dashboard');
        }

        if (Auth::guard('user')->check()) {
            return redirect('/');
        }

        return $next($request);
    }
}