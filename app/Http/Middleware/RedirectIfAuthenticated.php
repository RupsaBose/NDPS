<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(Auth::guard($guard)->check() && Auth::user()->user_type=='high_court') {
            return redirect('dashboard');
        }
        else if(Auth::guard($guard)->check() && (Auth::user()->user_type=='ps' || Auth::user()->user_type=='agency')) {
            return redirect('entry_form');
        }
        else if(Auth::guard($guard)->check() && Auth::user()->user_type=='magistrate') {
            return redirect('magistrate_entry_form');
        }
        else if(Auth::guard($guard)->check() && Auth::user()->user_type=='special_court') {
            return redirect('dashboard_special_court');
        }



        return $next($request);
    }
}
