<?php

namespace App\Http\Middleware;

use Closure;
use Auth; //added

class Accountant
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
        if (Auth::check() && Auth::user()->user_type == 'accountant'){
            return $next($request);
        } else {
            return redirect('/');
        }    
    }
}
