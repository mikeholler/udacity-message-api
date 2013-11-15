<?php

use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Laravel IoC binding class to allow for exception based error handling.
 *
 * Automatically converts any instance of Symfony\Component\HttpKernel\Exception\HttpExceptionInterface
 * thrown in the application to a response reflecting the contents of the exception.
 */
class ErrorServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->error(function(HttpException $e)
        {
            // Show a message if there is one, otherwise display the status code.
            return Response::json(['error' => $e->getMessage() ?: $e->getStatusCode()], $e->getStatusCode());
        });
    }

}