<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e) {
            if ($e instanceof ValidationException) {
                return response()->json([
                    'status_code' => 422,
                    'success' => false,
                    'message' => 'The given data was invalid.',
                    'validations' => $e->errors(),
                ], 422);
            } else if ($e instanceof UnauthorizedException) {
                return response()->json([
                    'status_code' => 401,
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            } else {
                if ($e->getMessage() == "Unauthenticated") {
                    return response()->json([
                        'status_code' => 401,
                        'success' => false,
                        'message' => 'Unauthorized'
                    ], 401);
                }
                return response()->json([
                    'status_code' => 404,
                    'success' => false,
                    'message' => $e->getMessage()
                ], 404);
            }
        });
    })->create();
