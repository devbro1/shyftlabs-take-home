<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Support\Reflector;
use Illuminate\Support\Str;

class ImplicitBindingFixerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        // make sure all outputs are json and not html
        $request->headers->set('Accept', 'application/json');

        $route = $request->route();
        $parameters = $route->parameters();
        foreach ($route->signatureParameters(UrlRoutable::class) as $parameter) {
            if (!$parameterName = static::getParameterName($parameter->getName(), $parameters)) {
                continue;
            }

            $parameterValue = $parameters[$parameterName];

            if ($parameterValue instanceof UrlRoutable) {
                continue;
            }

            $class_name = Reflector::getParameterClassName($parameter);
            $instance = new $class_name();
            settype($parameterValue, $instance->getKeyType());
            $route->setParameter($parameterName, $parameterValue);
        }

        return $next($request);
    }

    protected static function getParameterName($name, $parameters)
    {
        if (array_key_exists($name, $parameters)) {
            return $name;
        }

        if (array_key_exists($snakedName = Str::snake($name), $parameters)) {
            return $snakedName;
        }
    }
}
