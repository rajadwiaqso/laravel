<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BuyerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // if(Auth::user()->is_verified == false){
        //     return redirect()->route('verify.view');
        // }
        // else{

        if(!Auth::check()){
            return redirect()->route('signin.view');
        }
        
        
        if(Auth::user()->role == 'buyer'){

         
            return $next($request);
        }
        
        else if (Auth::user()->role == 'admin'){
        return redirect()->route('admin.index');
        }

        else if (Auth::user()->role == 'seller'){
            return redirect()->route('seller.index');
        }
    // }
        return redirect()->route('signin.view');
    }
}
