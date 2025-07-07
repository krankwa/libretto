<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CheckTokenExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated requests
        if ($request->bearerToken()) {
            $tokenParts = explode('|', $request->bearerToken());
            
            if (count($tokenParts) === 2) {
                $tokenId = $tokenParts[0];
                
                // Check if token exists and is not expired
                $token = DB::table('personal_access_tokens')
                    ->where('id', $tokenId)
                    ->first();
                
                if ($token && $token->expires_at && Carbon::now()->greaterThan($token->expires_at)) {
                    // Token is expired, delete it
                    DB::table('personal_access_tokens')
                        ->where('id', $tokenId)
                        ->delete();
                    
                    return response()->json([
                        'message' => 'Token has expired. Please login again.',
                        'error' => 'TOKEN_EXPIRED'
                    ], 401);
                }
            }
        }

        return $next($request);
    }
}
