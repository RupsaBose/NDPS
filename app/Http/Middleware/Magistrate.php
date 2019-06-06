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
            return $next($request)->header('Cache-Control','no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma','no-cache')
            ->header('Expires','Sun, 02 Jan 1990 00:00:00 GMT');
            
        else if(Auth::check() && (Auth::user()->user_type=='ps' || Auth::user()->user_type=='agency'))
            return redirect('entry_form');
        else if(Auth::check() && Auth::user()->user_type=='special_court')
            return redirect('dashboard_special_court');
        else if(Auth::check() && Auth::user()->user_type=='high_court')
            return redirect('dashboard');
    }
}
