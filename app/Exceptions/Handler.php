<?php

namespace App\Exceptions;

use Exception;
use App\Traits\RestTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Traits\RestExceptionHandlerTrait;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use RestTrait;
    use RestExceptionHandlerTrait;

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
     * @param Exception|Throwable $e
     * @return void
     * @throws Exception
     */
    public function report(Exception|Throwable $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception|Throwable $e
     * @return Response|JsonResponse
     * @throws Throwable
     */
    public function render($request, Exception|Throwable $e)
    {
        if(!$this->isApiCall($request) || env('APP_DEBUG')) {
            $retval = parent::render($request, $e);
        } else {
            $retval = $this->getJsonResponseForException($e);
        }

        return $retval;
    }
}
