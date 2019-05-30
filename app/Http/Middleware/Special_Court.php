<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Special_Court
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
        if(Auth::check() && Auth::user()->user_type=='special_court')
            return $next($request);
        else if(Auth::check() && (Auth::user()->user_type=='ps' || Auth::user()->user_type=='agency'))
            return redirect('entry_form');
        else if(Auth::check() && Auth::user()->user_type=='magistrate')
            return redirect('magistrate_entry_form');
        else if(Auth::check() && Auth::user()->user_type=='high_court')
            return redirect('dashboard');
    }
}
