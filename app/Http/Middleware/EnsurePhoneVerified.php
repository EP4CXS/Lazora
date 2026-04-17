<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhoneVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->phone_number && ! $user->phone_verified_at && ! $request->routeIs('phone-otp.*')) {
            return redirect()->route('phone-otp.show');
        }

        return $next($request);
    }
}
