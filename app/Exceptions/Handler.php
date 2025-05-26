<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\ApiResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [
        //
    ];

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log all exceptions with context
            Log::error('Exception occurred', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'url' => request()->url(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });
    }

    public function render($request, Throwable $e)
    {
        // Handle API requests
        if ($request->is('api/*') || $request->expectsJson()) {
            return $this->handleApiException($request, $e);
        }

        // Handle web requests
        return $this->handleWebException($request, $e);
    }

    private function handleApiException(Request $request, Throwable $e)
    {
        // Custom exceptions
        if ($e instanceof CompetitionException || 
            $e instanceof TypingException || 
            $e instanceof UserException) {
            return ApiResponse::error($e->getMessage(), $e->getCode());
        }

        // Validation exceptions
        if ($e instanceof ValidationException) {
            return ApiResponse::error('Validation failed', 422, $e->errors());
        }

        // Authentication exceptions
        if ($e instanceof AuthenticationException) {
            return ApiResponse::error('Authentication required', 401);
        }

        // Authorization exceptions
        if ($e instanceof AuthorizationException) {
            return ApiResponse::error('Access denied', 403);
        }

        // Model not found exceptions
        if ($e instanceof ModelNotFoundException) {
            return ApiResponse::error('Resource not found', 404);
        }

        // Database exceptions
        if ($e instanceof \Illuminate\Database\QueryException) {
            Log::error('Database error', ['exception' => $e]);
            return ApiResponse::error('Database error occurred', 500);
        }

        // Generic server errors
        if (config('app.debug')) {
            return ApiResponse::error($e->getMessage(), 500, [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return ApiResponse::error('Internal server error', 500);
    }

    private function handleWebException(Request $request, Throwable $e)
    {
        // Custom exceptions with user-friendly messages
        if ($e instanceof CompetitionException) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        if ($e instanceof TypingException) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        if ($e instanceof UserException) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        // Let Laravel handle other exceptions
        return parent::render($request, $e);
    }
}