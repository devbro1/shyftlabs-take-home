<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class AclEnforcerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        $route = $request->route();
        $user = $request->user();

        if ('Closure' === $route->getActionName()) {
            return $next($request);
        }

        [$controller_class,$controller_method] = explode('@', $route->getActionName());
        $parameters = $route->parameters();
        preg_match('/App\\\\Http\\\\Controllers\\\\(.*)Controller/', $controller_class, $matches);
        $policy_class = $this->getPolicyFor($matches[1]);

        if (class_exists($policy_class)) {
            $conversion = [];
            $conversion[$controller_method] = $controller_method;
            $conversion['index'] = 'viewAny';
            $conversion['show'] = 'view';
            $conversion['create'] = 'create';
            $conversion['store'] = 'create';
            $conversion['edit'] = 'update';
            $conversion['update'] = 'update';
            $conversion['destroy'] = 'delete';

            $controller_method = $conversion[$controller_method];

            $is_it_allowed = false;
            if (in_array($controller_method, get_class_methods($policy_class))) {
                $policy = new $policy_class();

                $is_it_allowed = call_user_func_array([$policy, $controller_method], array_merge([$user], array_values($parameters)));
            }

            if (true !== $is_it_allowed) {
                if (is_string($is_it_allowed)) {
                    throw new \Illuminate\Auth\Access\AuthorizationException($is_it_allowed);
                }

                throw new \Illuminate\Auth\Access\AuthorizationException();
            }
        }

        return $next($request);
    }

    protected function getPolicyFor($name)
    {
        return 'App\\Policies\\'.$name.'Policy';
    }
}
