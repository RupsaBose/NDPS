<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Magistrate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check() && Auth::user()->user_type=='magistrate')
            return $next($request);
        else if(Auth::check() && (Auth::user()->user_type=='ps' || Auth::user()->user_type=='agency'))
            return redirect('entry_form');
        else if(Auth::check() && Auth::user()->user_type=='special_court')
            return redirect('dashboard_special_court');
        else if(Auth::check() && Auth::user()->user_type=='high_court')
            return redirect('dashboard');
    }
}
