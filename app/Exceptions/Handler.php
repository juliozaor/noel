<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function render($request, Throwable $exception)
    {
        // Verifica si la solicitud espera una respuesta JSON
        if ($request->wantsJson()) {
            $statusCode = 500; // Por defecto, se usa 500 para errores no manejados
            
            // Define el mensaje de error predeterminado
            $message = 'Ocurrió un error en la solicitud.';

            if ($exception instanceof HttpException) {
                $statusCode = $exception->getStatusCode();
                $message = $exception->getMessage();
            } elseif ($exception instanceof ModelNotFoundException) {
                $statusCode = 404; // Por ejemplo, si el modelo no se encuentra
                $message = 'Recurso no encontrado.';
            } elseif ($exception instanceof AuthorizationException) {
                $statusCode = 403; // Por ejemplo, si no está autorizado
                $message = 'No está autorizado para acceder a este recurso.';
            }

            return response()->json(['error' => $message], $statusCode);
        }

        return parent::render($request, $exception);
    }
}
