<?php

use App\Shared\Infrastructure\Constants\Messages;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {})
        //
    ->withExceptions(function (Exceptions $exceptions): void {
        //for unauthenticated ( if header has accecpt json);
        $exceptions->render(function (AuthenticationException $e, Request $request) {
        if ($request->is('api/*')) {
            return response()->json([
                'success' =>false,
                'message' => Messages::UNAUTHENTICATED,
                'message' => 'Unauthenticated. Please provide a valid API token.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        });
        //response for custom request errors
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => Messages::VALIDATION_FAILED,
                    'errors' => $e->errors(), // Your custom structure here
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        });
    })->create();
