<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = $request->user();
        $user->tokens()->delete();

        $token = $user->createToken('api_token');
        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        /**
         * @var $user App\Models\User
         */

        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        try {
            // Revoke all tokens for the authenticated user
            $user->tokens()->delete();
        } catch (\Exception $e) {
            // Log the exception for investigation
            Log::error('Token revocation failed: ' . $e->getMessage());

            return response()->json(['error' => 'Unable to revoke tokens'], 500);
        }

        return response()->json(['message' => 'Tokens revoked successfully']);
    }
}
