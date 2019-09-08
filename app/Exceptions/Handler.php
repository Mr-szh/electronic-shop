<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    // public function render($request, Exception $exception)
    // {
    //     if ($this->isHttpException($exception)) {
    //         if ($exception->getStatusCode() == 404) {
    //             return response()->view('errors.' . '404', [], 404);
    //         }
    //     }

    //     return parent::render($request, $exception);
    // }
    public function render($request, Exception $exception)
    {
        if ($this->isHttpException($exception)) {
            $msg = '没有操作权限！';
            $error = $exception->getStatusCode();

            if ($exception->getStatusCode() == 404) {
                $msg = '页面找不到！';
                return response()->view('pages.error', ['error' => $error, 'msg' => $msg], 404);
            } else if ($exception->getStatusCode() == 405) {
                $msg = '页面错误！';
                return response()->view('pages.error', ['error' => $error, 'msg' => $msg], 405);
            }

            return response()->view('pages.error', ['error' => $error, 'msg' => $msg]);
        }

        return parent::render($request, $exception);
    }
}
