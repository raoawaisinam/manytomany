<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckAuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
       
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }
        $authUser = Auth::guard('api')->user();

        if (!$authUser) {
            return response()->json(['message' => 'Invalid token'], 401);
        }
        
        $request->setUserResolver(function () use ($authUser) {
            return $authUser;
        });

        return $next($request);
    }
}
