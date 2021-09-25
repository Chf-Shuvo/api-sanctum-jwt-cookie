<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
    // overriding the original handle function 
    public function handle($request, Closure $next, ...$guards)
    {
        // we are retrieving the jwt token from the cookie and adding it to the header as Authorization Bearer
        if($jwt = $request->cookie('jwt')){
            $request->headers->set('Authorization','Bearer '.$jwt);
        }
        $this->authenticate($request, $guards);

        return $next($request);
    }
}
