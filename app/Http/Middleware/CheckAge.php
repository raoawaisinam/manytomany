<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAge
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
        $age = $request->input('age');

        if ($age >= 18 && $age <= 27) {
            return $next($request);
        }

        if ($age < 18) {
            return response()->json(['message' => 'You must be at least 18 years old to register.'], Response::HTTP_FORBIDDEN);
        }

        if ($age > 27) {
            return response()->json(['message' => 'You must be 27 years old or younger to register.'], Response::HTTP_FORBIDDEN);
        }

        return response()->json(['message' => 'You are not allowed to register.'], Response::HTTP_FORBIDDEN);
    }
}

