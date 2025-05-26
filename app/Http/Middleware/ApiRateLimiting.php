<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Resources\ApiResponse;

class ApiRateLimiting
{
    public function handle(Request $request, Closure $next, string $limits = '60,1')
    {
        [$maxAttempts, $decayMinutes] = explode(',', $limits);
        
        $key = $this->resolveRequestSignature($request);
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);
            
            return ApiResponse::error(
                'Too many requests. Please try again later.',
                429,
                ['retry_after' => $retryAfter]
            )->header('Retry-After', $retryAfter);
        }
        
        RateLimiter::hit($key, $decayMinutes * 60);
        
        $response = $next($request);
        
        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', RateLimiter::remaining($key, $maxAttempts));
        $response->headers->set('X-RateLimit-Reset', RateLimiter::availableIn($key));
        
        return $response;
    }
    
    protected function resolveRequestSignature(Request $request): string
    {
        if ($request->user()) {
            return 'api:' . $request->user()->id;
        }
        
        return 'api:' . $request->ip();
    }
}