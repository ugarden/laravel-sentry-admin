<?php

namespace App\Exceptions;

use App\Classes\Exceptions\InvalidSignException;
use Exception;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ValidationException)
            return response()->json(['errno' => 4001, 'errmsg' => implode(',', $e->getMessageProvider()->getMessageBag()->all())]);

        if ($e instanceof QueryException)
            return response()->json(['errno' => 5001, 'errmsg' => '数据库错误']);

        if ($e instanceof ModelNotFoundException)
            return response()->json(['errno' => 4003, 'errmsg' => $e->getMessage()]);

        if ($e instanceof InvalidSignException)
            return response()->json(['errno' => 4002, 'errmsg' => 'sign无效']);

        return parent::render($request, $e);
    }
}
