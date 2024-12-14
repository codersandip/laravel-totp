<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Totp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->check()) {
            if(!$request->cookie('totp') && $request->route()->getName() != 'login-totp') {
                return redirect()->route('login-totp'); 
            }
        }
        if (auth()->check() && !auth()->user()->totp_enabled && $request->route()->getName() == 'login-totp') {
            return redirect()->intended('home');
        }
        return $next($request);
    }


    protected function check() : bool {
        return auth()->check() && auth()->user()->totp_enabled && auth()->user()->totp_secret;
    }
}
