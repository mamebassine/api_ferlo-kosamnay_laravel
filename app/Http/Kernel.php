<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Tymon\JWTAuth\Http\Middleware\Authenticate as JWTAuthenticate;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Middleware global
    ];

    protected $routeMiddleware = [
        // Middleware spécifiques aux routes
        'auth' => JWTAuthenticate::class, // Renommez si nécessaire
        // Autres middlewares
    ];
}
