<?php

// source: https://gist.github.com/yedincisenol/4951db2b01ac5d2bfebf969b1e1f6866

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class BooleanConvertorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function handle(Request $request, \Closure $next)
    {
        $request->replace($this->transform($request->all()));

        return $next($request);
    }

    private function transform(array $parameters): array
    {
        return collect($parameters)->map(function ($param) {
            if ('true' === $param || 'false' === $param) {
                return filter_var($param, FILTER_VALIDATE_BOOLEAN);
            }

            return $param;
        })->all();
    }
}
