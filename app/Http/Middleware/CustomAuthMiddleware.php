<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get the Authorization header
        $header = $request->header('Authorization');

        // Check if the header exists and matches the Bearer token pattern
        if ($header && preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            $token = $matches[1];

            // Find the token in the database
            $tokenRecord = DB::table('oauth_access_tokens')
                ->where('id', $token)  // Assuming 'id' is used for the token
                ->where('revoked', false)
                ->first();

            // If the token is valid, proceed with the request
            if ($tokenRecord) {
                // Find the associated user
                $user = DB::table('users')->find($tokenRecord->user_id);

                if ($user) {
                    // Set the authenticated user for the request
                    $request->setUserResolver(fn() => $user);

                    return $next($request);
                }
            }
        }

        // If the token is invalid or missing, return unauthorized response
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}


