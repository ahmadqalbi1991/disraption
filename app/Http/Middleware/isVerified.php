<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class isVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {


        if (Auth::check() && (Auth::user()->verified != 0)) {
            return $next($request);
        }

        // reutrn echo json array response 401
        return response()->error("Your account is not verified!");
    }
}
