<?php

use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ErrorServiceProvider extends ServiceProvider {

    public function register() {
        $this->app->error(function(HttpException $e) {
            return Response::json(['error' => $e->getMessage() ?: $e->getStatusCode()], $e->getStatusCode());
        });
    }

}