<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Verify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check()){
        if (Auth::user()->is_verified == true){
            return $next($request);
            
        }
        else if (Auth::user()->is_verified == false){
            return redirect()->route('verify.view');
        }
    }

    return $next($request);
    }
}
