<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        
        // if (Auth::guard('pengguna')->check() && Auth::guard('pengguna')->user()->jabatan == $role) {
        //     return $next($request);
        // }

        if (Auth::guard()->check()) {
            // Periksa apakah peran pengguna sesuai dengan salah satu dari peran yang diberikan
            foreach ($roles as $role) {
                if (Auth::guard()->user()->role == $role) {
                    return $next($request);
                }
            }
        }

        return redirect()->to(route('login'));
    }
}
