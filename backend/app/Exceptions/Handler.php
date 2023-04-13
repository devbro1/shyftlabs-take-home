<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e) {
            if (false !== strpos($e->getMessage(), 'No query results for model')) {
                return response()->json(['status' => 'error', 'message' => 'Resource not found or access denied'], 404);
            }
            Log::error($e);

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 404);
        });

        $this->renderable(function (ValidationException $e, $request) {
            return response()->json(['status' => 'error', 'message' => 'Please check the errors and try again.', 'errors' => $e->validator->failed()], $e->status);
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json(['status' => 'error', 'message' => 'Method is not supported for this route.', 'errors' => $e], $e->status);
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json(['status' => 'error', 'message' => $exception->getMessage()], 401);
    }
}
