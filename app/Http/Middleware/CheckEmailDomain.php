<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckEmailDomain
{
    protected $allowedDomains = ['gmail.com', 'mail.com']; 

    public function handle(Request $request, Closure $next)
    {
        $email = $request->input('email');
        $domain = substr(strrchr($email, "@"), 1);
        if (in_array($domain, $this->allowedDomains)) {
            return $next($request);
        }
        return response()->json([
            'errors' => [  'email' => ['Registration using this email domain is not allowed.'],
            ],'allowed_domains' => $this->allowedDomains ], 422);
    }
}
