<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Http\Controllers\ApiController;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $api = new ApiController();
        $message = 'Sorry, something went wrong.';
        $extra = [];

        if ($e instanceof Tymon\JWTAuth\Exceptions\TokenExpiredException) {
            return $api->respondUnauthorizedError("token expired");
        } else if ($e instanceof Tymon\JWTAuth\Exceptions\TokenInvalidException) {
            return $api->respondBadRequestError("token invalid");
        } else if ($e instanceof Tymon\JWTAuth\Exceptions\JWTException) {
            return $api->respondUnauthorizedError("token absent");
        } else if ($e instanceof NotFoundHttpException) {
            $message = "The URI requested is invalid or the resource requested does not exist";
        }

        // If the app is in debug mode
        if (config('app.debug'))
        {
            // Add the exception class name, message and stack trace to response
            $extra['exception'] = get_class($e); // Reflection might be better here
            $extra['message'] = $e->getMessage();
            $extra['trace'] = $e->getTrace();
        }

        // Default response of 400
        $api->setStatusCode(400);

        // If this exception is an instance of HttpException
        if ($this->isHttpException($e))
        {
            // Grab the HTTP status code from the Exception
            $api->setStatusCode($e->getStatusCode());
        }

        // Return a JSON response with the response array and status code
        return $api->respondWithError($message, $extra);
    }
}
