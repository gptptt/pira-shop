<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Process the request
        $response = $next($request);
        
        // If the response is already a JsonResponse, return it
        if ($response instanceof JsonResponse) {
            return $response;
        }
        
        // For API routes, ensure all responses are JSON
        if ($request->is('api/*') && !$response instanceof JsonResponse) {
            // Convert the response data to JSON if it's not already
            $content = $response->getContent();
            $data = json_decode($content, true) ?? $content;
            
            return response()->json([
                'status' => $response->isSuccessful() ? 'success' : 'error',
                'data' => $data,
            ], $response->getStatusCode());
        }
        
        return $response;
    }
} 