<?php

namespace App\Http\Middleware;

use Session;
use Closure;

class UserMiddleware
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
        if ( is_null(Session::get('userData')) ) {
           return redirect()->route('/');
        }
         if ( empty($request->url() == '/checkout')) {
           // return redirect('/checkout')->with('msg','Please login to place your order');
        }

        return $next($request);
    }
}
