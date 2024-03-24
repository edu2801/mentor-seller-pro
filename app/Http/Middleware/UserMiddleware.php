<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->role === 1) {
            return redirect()->route('mentor.dashboard');
        }

        if (auth()->user()->role === 2) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
