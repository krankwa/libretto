<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class WorkingAuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Create token directly in personal_access_tokens table
        $tokenName = 'auth_token';
        $abilities = ['*'];
        $expiresAt = Carbon::now()->addDay();
        
        // Generate random token
        $plainTextToken = \Illuminate\Support\Str::random(40);
        $hashedToken = hash('sha256', $plainTextToken);
        
        // Insert token into database
        $tokenId = DB::table('personal_access_tokens')->insertGetId([
            'tokenable_type' => get_class($user),
            'tokenable_id' => $user->id,
            'name' => $tokenName,
            'token' => $hashedToken,
            'abilities' => json_encode($abilities),
            'expires_at' => $expiresAt,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fullToken = $tokenId . '|' . $plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $fullToken,
            'token_type' => 'Bearer',
            'expires_at' => $expiresAt->toISOString(),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ], 201);
    }

    /**
     * Login user with token expiration logic
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate user
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();

        // Check if user has existing valid tokens using raw queries
        $validToken = DB::table('personal_access_tokens')
            ->where('tokenable_type', get_class($user))
            ->where('tokenable_id', $user->id)
            ->where('expires_at', '>', Carbon::now())
            ->first();
        
        if ($validToken) {
            // Return response indicating existing valid token exists
            return response()->json([
                'message' => 'Login successful - using existing token',
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($validToken->expires_at)->toISOString(),
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'note' => 'You already have a valid token. Use /api/refresh to get a new token if needed.'
            ]);
        }

        // Delete all expired tokens for this user
        DB::table('personal_access_tokens')
            ->where('tokenable_type', get_class($user))
            ->where('tokenable_id', $user->id)
            ->where('expires_at', '<=', Carbon::now())
            ->delete();
        
        // Create new token
        $tokenName = 'auth_token';
        $abilities = ['*'];
        $expiresAt = Carbon::now()->addDay();
        
        // Generate random token
        $plainTextToken = \Illuminate\Support\Str::random(40);
        $hashedToken = hash('sha256', $plainTextToken);
        
        // Insert token into database
        $tokenId = DB::table('personal_access_tokens')->insertGetId([
            'tokenable_type' => get_class($user),
            'tokenable_id' => $user->id,
            'name' => $tokenName,
            'token' => $hashedToken,
            'abilities' => json_encode($abilities),
            'expires_at' => $expiresAt,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fullToken = $tokenId . '|' . $plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $fullToken,
            'token_type' => 'Bearer',
            'expires_at' => $expiresAt->toISOString(),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    /**
     * Logout user (revoke current token)
     */
    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if ($token) {
            [$id, $plainTextToken] = explode('|', $token, 2);
            $hashedToken = hash('sha256', $plainTextToken);
            
            DB::table('personal_access_tokens')
                ->where('id', $id)
                ->where('token', $hashedToken)
                ->delete();
        }

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get authenticated user details
     */
    public function user(Request $request)
    {
        $user = $request->user();
        $token = $request->bearerToken();
        $tokenData = null;
        
        if ($token) {
            [$id, $plainTextToken] = explode('|', $token, 2);
            $hashedToken = hash('sha256', $plainTextToken);
            
            $tokenData = DB::table('personal_access_tokens')
                ->where('id', $id)
                ->where('token', $hashedToken)
                ->first();
        }
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token_expires_at' => $tokenData ? Carbon::parse($tokenData->expires_at)->toISOString() : null,
            'token_created_at' => $tokenData ? Carbon::parse($tokenData->created_at)->toISOString() : null
        ]);
    }

    /**
     * Refresh token (create new token and revoke old one)
     */
    public function refresh(Request $request)
    {
        $user = $request->user();
        $token = $request->bearerToken();
        
        // Revoke current token
        if ($token) {
            [$id, $plainTextToken] = explode('|', $token, 2);
            $hashedToken = hash('sha256', $plainTextToken);
            
            DB::table('personal_access_tokens')
                ->where('id', $id)
                ->where('token', $hashedToken)
                ->delete();
        }
        
        // Create new token
        $tokenName = 'auth_token';
        $abilities = ['*'];
        $expiresAt = Carbon::now()->addDay();
        
        // Generate random token
        $plainTextToken = \Illuminate\Support\Str::random(40);
        $hashedToken = hash('sha256', $plainTextToken);
        
        // Insert token into database
        $tokenId = DB::table('personal_access_tokens')->insertGetId([
            'tokenable_type' => get_class($user),
            'tokenable_id' => $user->id,
            'name' => $tokenName,
            'token' => $hashedToken,
            'abilities' => json_encode($abilities),
            'expires_at' => $expiresAt,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fullToken = $tokenId . '|' . $plainTextToken;

        return response()->json([
            'message' => 'Token refreshed successfully',
            'access_token' => $fullToken,
            'token_type' => 'Bearer',
            'expires_at' => $expiresAt->toISOString(),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }
}
