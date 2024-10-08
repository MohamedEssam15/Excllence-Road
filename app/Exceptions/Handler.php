<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\UnauthorizedException;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, \Throwable $exception)
    {
        if ($exception instanceof AuthenticationException) {
            if ($request->expectsJson()) {
                return apiResponse(__('response.Unauthenticated'),new stdClass(),[__('response.Unauthenticated')],Response::HTTP_UNAUTHORIZED);
            }
            // For non-JSON requests, you can redirect to a custom page or handle it differently
            return redirect()->back()->with('error', __('response.Unauthenticated'));
        }
        if ($exception instanceof UnauthorizedException) {
            if ($request->expectsJson()) {
                return apiResponse(__('response.notAuthorized'),new stdClass(),[__('response.notAuthorized')],Response::HTTP_UNAUTHORIZED);
            }
            // For non-JSON requests, you can redirect to a custom page or handle it differently
            return redirect()->back()->with('error', __('response.notAuthorized'));
        }
        if ($exception instanceof NotFoundHttpException) {
            if ($request->expectsJson()) {
                return apiResponse(__('response.notFound'),new stdClass(),[__('response.notFound')],Response::HTTP_NOT_FOUND);
            }
            // For non-JSON requests, you can redirect to a custom page or handle it differently
            return redirect()->back()->with('error', __('response.notFound'));
        }
        if ($exception instanceof AccessDeniedHttpException) {
            if ($request->expectsJson()) {
                return apiResponse(__('response.notAuthorized'),new stdClass(),[__('response.notAuthorized')],Response::HTTP_FORBIDDEN);
            }
            // For non-JSON requests, you can redirect to a custom page or handle it differently
            return redirect()->back()->with('error', __('response.notAuthorized'));
        }

        return parent::render($request, $exception);
    }
}
