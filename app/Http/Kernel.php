<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
      \App\Http\Middleware\TrustHosts::class, // Descomentar si es necesario para tu configuración de host
        \App\Http\Middleware\TrustProxies::class, // Importante si tu aplicación está detrás de un balanceador de carga o proxy
        \Illuminate\Http\Middleware\HandleCors::class, // Manejo de CORS, crucial para APIs consumidas desde diferentes dominios
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class, // Valida el tamaño de las solicitudes POST
        \Illuminate\Foundation\Http\Middleware\TrimStrings::class, // Recorta espacios en blanco de los strings de entrada
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class, // Convierte strings vacíos a null
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
         \Illuminate\Session\Middleware\AuthenticateSession::class, // Descomentar si usas autenticación de sesión para web
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // Permite el route model binding
        ],

        'api' => [
         \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // Usar solo si tu API es para una SPA que usa cookies/sesiones con Sanctum
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api', // Limita la tasa de peticiones a la API (60 por minuto por defecto)
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // Permite el route model binding en rutas API
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used to conveniently assign middleware to routes and groups.
     * En versiones anteriores de Laravel, esta propiedad se llamaba $routeMiddleware.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class, // Middleware de autenticación por defecto
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class, // Autenticación HTTP Basic
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class, // Para controlar cabeceras de caché HTTP
        'can' => \Illuminate\Auth\Middleware\Authorize::class, // Para autorización basada en Gates y Policies
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // Redirige si el usuario ya está autenticado (para login/register web)
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class, // Para confirmación de contraseña
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class, // Para peticiones precognitivas (Laravel 10+)
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class, // Para validar rutas firmadas
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class, // Para aplicar rate limiting específico
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class, // Para rutas que requieren verificación de email

        // TU MIDDLEWARE PERSONALIZADO PARA ROLES:
        'role' => \App\Http\Middleware\CheckRole::class,
    ];
}
