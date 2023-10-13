<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param Throwable $e
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($request->wantsJson() && ( ! $e instanceof ValidationException))
        {
            // Default response of 400
            $status = 400;
            $response = [
                'status'=> false,
                'message'=> $e->getMessage()
            ];

            // If this exception is an instance of HttpException
            if ($this->isHttpException($e))
            {
                // Grab the HTTP status code from the Exception
                $status = $e->getCode();
            }

            // Return a JSON response with the response array and status code
            return response()->json($response, $status);
        }
        return parent::render($request, $e);
    }
}
