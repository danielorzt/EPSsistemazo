<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware as LaravelMiddlewareConfig; // Renombrado para evitar conflicto con tu clase personalizada

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (LaravelMiddlewareConfig $middleware) {
        // Aquí usualmente se configuran los alias de middleware de Sanctum si estás usando una SPA
        // $middleware->append(EnsureFrontendRequestsAreStateful::class); // Si tu frontend es SPA y necesita cookies/sesiones.

        // Si tu API es stateless y usa tokens (como con Sanctum), puedes omitir los CSRF tokens:
        // $middleware->validateCsrfTokens(except: [
        //     'api/*',
        // ]);

        // No necesitas registrar el middleware 'role' aquí si ya lo hiciste en app/Http/Kernel.php
        // Esta sección es principalmente para middlewares globales o de grupo definidos programáticamente.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Aquí puedes registrar tus controladores de excepciones personalizados si lo necesitas.
    })
    ->create();
