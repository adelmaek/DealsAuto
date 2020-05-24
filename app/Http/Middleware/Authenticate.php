<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    // protected function redirectTo($request)
    // {
    //     if (! $request->expectsJson()) {
    //         return route('login');
    //     }
    // }

    public function handle($request, Closure $next, $guard = null) //protect dashboard route
    {
        if (Auth::guard($guard)->guest()) {
            if($request->ajax())
            {
                return response('Unauthorized.',401);
            }
            else
            {  
                return redirect()->route('welcome');
            }
        }
        return $next($request);
    }
}
