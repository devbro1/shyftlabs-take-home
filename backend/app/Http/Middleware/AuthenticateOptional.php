<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;
use Closure;

class AuthenticateOptional extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return null|string
     */
    protected function redirectTo($request)
    {
        return null;
        // if (! $request->expectsJson()) {
        //     return route('login');
        // }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        $authorization_cookie = 'Authorization_token';

        if (!$request->headers->get('Authorization') && $request->cookie($authorization_cookie)) {
            $request->headers->set('Authorization', 'Bearer '.$request->cookie($authorization_cookie));
        }

        try {
            $this->authenticate($request, $guards);
        } catch (AuthenticationException $ex) {
        }

        return $next($request);
    }
}
