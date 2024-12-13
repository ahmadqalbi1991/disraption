<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class AuthenticateWithSanctum extends Middleware
{


    public static function authenticateGetUser($request)
    {

        // Check if token is provided in the request body
        $token = $request->input('access_token');

        if (!$token) {
            // If token is not provided in the body, check if it's provided in the headers
            $token = $request->bearerToken(); // Laravel provides a helper for fetching bearer tokens
        }

        if ($token) {
            // Set the token in the request headers
            $request->headers->set('Authorization', 'Bearer ' . $token);

            // Authenticate the user using Sanctum
            $user = Auth::guard('sanctum')->user();

            return $user;

        }

        return null;
    }


    public function handle($request, Closure $next, ...$guards)
    {

        // Check if token is provided in the request body
        $token = $request->input('access_token');

        if (!$token) {
            // If token is not provided in the body, check if it's provided in the headers
            $token = $request->bearerToken(); // Laravel provides a helper for fetching bearer tokens
        }

        if ($token) {
            // Set the token in the request headers
            $request->headers->set('Authorization', 'Bearer ' . $token);

            // Authenticate the user using Sanctum
            $user = Auth::guard('sanctum')->user();



            if ($user) {

                // Set the user to auth so that it can be accessed in the request using the default Auth::()
                Auth::setUser($user);

                // If user is authenticated, proceed with the request
                return $next($request);
            }
        }

        // If token is not provided or authentication fails, return unauthorized response
        return response()->unauthorized();
    }
}
