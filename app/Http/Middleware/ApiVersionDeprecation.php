<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiVersionDeprecation
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-API-Version', 'v1');
        $response->headers->set('X-API-Status', 'deprecated');
        $response->headers->set('X-API-Latest-Version', 'v2');
        $response->headers->set(
            'X-API-Migration',
            url('/api/v2/products')
        );
        $response->headers->set(
            'Sunset',
            'Fri, 01 Jan 2027 00:00:00 GMT'
        );
        $response->headers->set(
            'X-API-Deprecation-Message',
            'V1 is deprecated. Please migrate to V2 before January 1, 2027.'
        );

        return $response;
    }
}