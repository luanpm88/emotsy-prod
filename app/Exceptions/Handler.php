<?php

namespace Acelle\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Closure;
use Throwable;
use Acelle\Library\Notification\BackendError as BackendErrorNotification;
use App;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        if (in_array(get_class($exception), $this->dontReport)) {
 
            // Do nothing
        } elseif (App::runningInConsole() && isInitiated()) {
            try {
                // IMPORTANT: do not use Model here, what if DB connect is not initiated correctly
                // resulting in "exception loop stack" => "PHP Fatal error:  Uncaught Error: Maximum function nesting level of '256' reached, aborting"
                $title = 'PHP CLI ERROR';
                BackendErrorNotification::cleanupDuplicateNotifications($title); // keep last error only, otherwise, get overwhelmed
                BackendErrorNotification::warning([
                    'title' => $title,
                    'message' => sprintf("[%s] %s: %s", date('Y-m-d'), get_class($exception), $exception->getMessage())], false); // false: also clean up other notification logged by BackendErrorNotification
            } catch (Throwable $t) {
                // just keep silent in case of even ErrorNotification cannot work, in case of DB connect for example
            }
        }

        parent::report($exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        // With 404 error, no way to use response()->view
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            // Default to
            // return view('errors.404');
        }

        return parent::render($request, $e);
    }
}
