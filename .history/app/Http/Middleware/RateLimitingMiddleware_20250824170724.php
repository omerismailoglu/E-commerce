<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter as RateLimiterFacade;

/**
 * Rate Limiting Middleware
 * 
 * Implements IP-based rate limiting to prevent API abuse
 * Limits requests to 60 per minute per IP address
 */
class RateLimitingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $key = $request->ip();
        $maxAttempts = 60; // Maximum 60 requests per minute
        $decayMinutes = 1;

        // Check if rate limit exceeded
        if (RateLimiterFacade::tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => RateLimiterFacade::availableIn($key)
            ], 429);
        }

        // Increment request count
        RateLimiterFacade::hit($key, $decayMinutes * 60);

        $response = $next($request);

        // Add rate limit headers to response
        return $response->header('X-RateLimit-Limit', $maxAttempts)
                       ->header('X-RateLimit-Remaining', RateLimiterFacade::remaining($key, $maxAttempts));
    }
}
