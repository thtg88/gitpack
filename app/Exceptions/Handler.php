<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            return $this->renderJson($request, $e);
        }

        if ($e instanceof TokenMismatchException) {
            return back()->withInput($request->except($this->dontFlash))
                ->with(
                    'error',
                    'There were some problems with your request. Please try again.'
                );
        }

        return parent::render($request, $e);
    }

    /**
     * Render an exception into a JSON HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    protected function renderJson(Request $request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $msg = 'Resource not found.';

            return response()->json(
                ['errors' => ['resource_not_found' => [$msg]]],
                404
            );
        }

        if ($e instanceof NotFoundHttpException) {
            $msg = $e->getMessage() ?: 'Resource not found.';

            return response()->json(
                ['errors' => ['resource_not_found' => [$msg]]],
                404
            );
        }

        if ($e instanceof AuthenticationException) {
            $msg = $e->getMessage() ?: 'Unauthenticated.';

            return response()->json(
                ['errors' => ['unauthenticated' => [$msg]]],
                401
            );
        }

        if ($e instanceof AuthorizationException) {
            $msg = $e->getMessage() ?: 'Forbidden.';

            return response()->json(
                ['errors' => ['forbidden' => [$msg]]],
                403
            );
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json(
                ['errors' => ['method_not_allowed' => ['Method not allowed.']]],
                405
            );
        }

        if ($e instanceof ThrottleRequestsException) {
            $msg = $e->getMessage() ?: 'Too Many Attempts.';

            return response()->json(
                ['errors' => ['too_many_attempts' => [$msg]]],
                429
            );
        }

        if ($e instanceof HttpException) {
            $status_code = $e->getStatusCode();

            if ($status_code == 401) {
                $msg = $e->getMessage() ?: 'Unauthorized.';

                return response()->json(
                    ['errors' => ['unauthorized' => [$msg]]],
                    $status_code
                );
            }

            if ($status_code == 403) {
                $msg = $e->getMessage() ?: 'Forbidden.';

                return response()->json(
                    ['errors' => ['forbidden' => [$msg]]],
                    $status_code
                );
            }

            if ($status_code == 404) {
                $msg = $e->getMessage() ?: 'Resource not found.';

                return response()->json(
                    ['errors' => ['resource_not_found' => [$msg]]],
                    $status_code
                );
            }
        }

        return parent::render($request, $e);
    }
}
