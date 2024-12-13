<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class isCustomer
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

        if (Auth::check() && (Auth::user()->user_type_id == '2')) {
            return $next($request);
        }

        // reutrn echo json array response 401
        return response()->json([
            "isSuccess" => "false",
            "status"   => "401",
            "message" => "Unauthorized!",
            "data" => null,
            "errors" => null
        ], 401);
    }
}
