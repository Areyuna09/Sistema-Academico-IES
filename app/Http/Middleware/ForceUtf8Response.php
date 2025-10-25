<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceUtf8Response
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Force UTF-8 encoding for all responses
        $contentType = $response->headers->get('Content-Type');

        if ($contentType) {
            // Si ya tiene Content-Type, agregar charset si no lo tiene
            if (!str_contains($contentType, 'charset')) {
                if (str_contains($contentType, 'json')) {
                    $response->headers->set('Content-Type', 'application/json; charset=UTF-8', false);
                } elseif (str_contains($contentType, 'html')) {
                    $response->headers->set('Content-Type', 'text/html; charset=UTF-8', false);
                }
            }
        }

        return $response;
    }
}
