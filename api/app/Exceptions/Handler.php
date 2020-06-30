<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        Log::debug('render(), request: ' . $request . ', exception: ' . $exception);
        
        if ($exception instanceof NotFoundHttpException) {
            $statusCode = 404;
            $message = "Not found";
        } elseif ($exception instanceof AuthenticationException) {
            $statusCode = 401;
            $message = "Unauthorized";
        } else {
            $statusCode = 500;
            $message = "Internal Error";
        }
        
        Log::debug('statusCode: ' . $statusCode); 
    
        return response()->json([
            "message" => $message,
            "status" => "ERROR",            
            "exception" => $exception
        ], $statusCode);
    }
}