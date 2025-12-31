<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
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
        $this->renderable(function (ThrottleRequestsException $e, $request) {
            if ($request->header('X-Inertia')) {
                $retryAfter = $e->getHeaders()['Retry-After'];
                $message = "Too many attempts. Please try again. {$retryAfter}";

                // Manually create a validator and throw a ValidationException
                // to ensure it's handled by Inertia's core form handling.
                $validator = \Illuminate\Support\Facades\Validator::make([], []); // Empty validator
                $validator->errors()->add('code', $message);

                throw new ValidationException($validator);
            }
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
