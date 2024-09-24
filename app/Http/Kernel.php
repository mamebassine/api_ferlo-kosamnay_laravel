<?php

namespace App\Http;

use App\Http\Middleware\JwtMiddleware; // Assurez-vous d'importer votre nouveau middleware
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Middleware global
    ];

    protected $routeMiddleware = [
        // Middleware spécifiques aux routes
        'jwt.auth' => JwtMiddleware::class, // Remplacez par votre middleware
        // Autres middlewares
    ];
}

