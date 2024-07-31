<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Passport\Token;
use Symfony\Component\HttpFoundation\Response;

class CheckToken
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get the Authorization header
        $header = $request->header('Authorization');

        // Check if the header exists and matches the Bearer token pattern
        if ($header && preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            $token = $matches[1];

            // Find the token using Passport
            $personalAccessToken = Token::where('id', $token)->first();

            // If the token is valid, proceed with the request
            if ($personalAccessToken && !$personalAccessToken->revoked) {
                // Set the authenticated user for the request
                $request->setUserResolver(fn() => $personalAccessToken->user);

                return $next($request);
            }
        }

        // If the token is invalid or missing, return unauthorized response
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
