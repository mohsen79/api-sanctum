<?php

namespace App\Exceptions;

use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            switch ($exception->getModel()) {
                case 'App\Models\User':
                    $ex = new UserNotFoundException();
                    return $ex->message($exception->getIds()[0]);
                    break;
                case 'App\Models\Post':
                    $ex = new PostNotFoundException();
                    return $ex->message($exception->getIds()[0]);
                    break;
                case 'App\Models\Comment':
                    $ex = new CommentNotFoundException();
                    return $ex->message($exception->getIds()[0]);
                    break;
            }
        }
        return parent::render($request, $exception);
    }
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
