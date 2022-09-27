<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class ForceJsonResponse
 *
 * @package KDServices\Core\Http\Middleware
 *
 */
class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        /**
         * Чтобы наше приложение всегда возвращало json независимо от переданных заголовков
         */
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
