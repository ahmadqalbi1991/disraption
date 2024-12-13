<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('success', function (string $message, $object = null, $extraData = null) {

            if (is_null($object)) {
                $object = (object)[];
            }

            return response()->json([
                "status"   => "1",
                "code" => "200",
                "isSuccess" => "true",
                "message" => $message,
                "data"  => $object,
                "extraData" => $extraData,
                "errors" => null
            ], 200);
        });

        Response::macro('error', function (string $message, $e = []) {
            return response()->json([
                "isSuccess" => "false",
                "status"   => '0',
                "code" => "200",
                "message" => $message,
                "data" => (object)[],
                "errors"  => $e
            ], 200);
        });


        Response::macro('unauthorized', function () {
            return response()->json([
                "isSuccess" => "false",
                "status"   => "0",
                "code" => "401",
                "message" => "Unauthorized!",
                "data" => (object)[],
                "errors" => []
            ], 401);
        });

        Response::macro('not_found', function () {
            return response()->json([
                "isSuccess" => "false",
                "status"   => 404,
                "code" => "404",
                "message" => "Route not found!",
                "data" => null,
                "error" => null
            ], 404);
        });
    }
}
