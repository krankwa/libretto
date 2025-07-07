<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if ($user) {
            try {
                $token = $user->currentAccessToken();
                
                if ($token && $token->expires_at) {
                    // Check if token is expired
                    if (Carbon::now()->greaterThan($token->expires_at)) {
                        // Delete expired token
                        $token->delete();
                        
                        return response()->json([
                            'message' => 'Token has expired. Please login again.',
                            'error' => 'TOKEN_EXPIRED'
                        ], 401);
                    }
                }
            } catch (\Exception $e) {
                // If there's any issue with token checking, just continue
                // This prevents the middleware from breaking the request
            }
        }

        return $next($request);
    }
}
