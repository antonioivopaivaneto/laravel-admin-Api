<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {


        // Personalizando a renderização de exceções
        $exceptions->render(function (Throwable $exception, $request) {
            if ($exception instanceof AuthorizationException) {
                // Captura a AuthorizationException e retorna apenas a mensagem
                return response()->json([
                    'message' => 'This action is unauthorized.'
                ], 403); // Código de status HTTP 403 para acesso negado
            }

            if ($exception instanceof HttpExceptionInterface) {
                // Captura outras exceções HTTP e retorna apenas a mensagem
                return response()->json([
                    'message' => $exception->getMessage(),
                ], $exception->getStatusCode());
            }

            // Para outras exceções, retorne uma resposta genérica com código 500
            return response()->json([
                'message' => 'An unexpected error occurred.'
            ], 500);
        });
    })->create();
