<?php

use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Laravel IoC bindings class supporting model dependency injection in controllers.
 */
class ModelServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind('GroupModel');
        $this->app->bind('MemberModel');
        $this->app->bind('MessageModel');
        $this->app->bind('UserModel');
    }

}