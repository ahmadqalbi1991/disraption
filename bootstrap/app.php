<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use App\Http\Middleware\AuthenticateWithSanctum;
use App\Http\Middleware\isCustomer;
use App\Http\Middleware\isVerified;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Enable restfull api
        $middleware->statefulApi();


        // ------- Global Middleware ------- //

        $middleware->append([
            PreventRequestsDuringMaintenance::class,
            ValidatePostSize::class,
            TrimStrings::class,
            ConvertEmptyStringsToNull::class,

        ]);
        // --------------------------------- //
       
        
        // ------ Middleware With Alias ------ //
        $middleware->alias([
            'sanctum.custom_token' => AuthenticateWithSanctum::class,
            // Customer Middleware
            'customer' => isCustomer::class,
            'is_verified' => isVerified::class,
            // Admin Middleware
            'admin' => IsAdmin::class,
            // Vendor Middleware
            'vendor' => \App\Http\Middleware\Vendor::class,
            // Vendor Verified Middleware
            'is_vendor_verified' => \App\Http\Middleware\Vendor::class,
        ]);


     
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
