<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * XSS Protection Middleware
 * 
 * Sanitizes all request inputs to prevent XSS attacks
 * Removes HTML tags and encodes special characters
 */
class XSSProtectionMiddleware
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
        $input = $request->all();
        
        // Sanitize all input data to prevent XSS attacks
        array_walk_recursive($input, function(&$value) {
            // Remove HTML tags
            $value = strip_tags($value);
            // Encode special characters
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        });
        
        // Replace request data with sanitized input
        $request->merge($input);
        
        return $next($request);
    }
}
