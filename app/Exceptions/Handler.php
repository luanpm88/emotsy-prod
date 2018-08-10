<?php

namespace Acelle\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * @param \Exception $e
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        // With 404 error, no way to use response()->view
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return \Response::make(sprintf('<h1>404 - Not found</h1><p>Go back to your home page at <a href="%1$s">%1$s</a></p>', url('/')), 404);
        }
        
        // check if the exception does match the known list
        foreach ($this->customErrors() as $key => $value) {
            if ($key == get_class($e)) {
                // if the exception message does match
                if (array_key_exists('match', $value)) {
                    if (strpos($e->getMessage(), $value['match']) !== false) {
                        // error description does match, ok
                    } else {
                        continue;
                    }
                }
                return response()->view('errors.custom', $value, 500);
            }
        }
        
        return parent::render($request, $e);
    }
    
    private function customErrors()
    {
        return [
            \PDOException::class => [
                'title' => 'Cannot connect to MySQL',
                'message' => 'Make sure MySQL service is running and MySQL connection settings in <code>.env</code> and <code>bootstrap/cache/config.php</code> files are correct'
            ],
            \RuntimeException::class => [
                'match' => 'No supported encrypter found. The cipher and / or key length are invalid',
                'title' => 'File missing',
                'message' => 'The <code>.env</code> file is missing. Make sure you have uploaded the file to the server',
            ]
        ];
    }
}
