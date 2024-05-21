<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceAcceptsJsonHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Laravel assumes requests don't want JSON if it's not specifically requested (and enables things like
        //redirecting to a login route) - we'll just force the request to always ask for JSON. Not the nicest solution,
        //but so long as the API only needs to deal in JSON this generally shouldn't cause major issues.
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
