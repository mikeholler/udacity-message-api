<?php

use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ModelServiceProvider extends ServiceProvider {

    public function register() {
        $this->app->bind('GroupModel');
        $this->app->bind('MemberModel');
        $this->app->bind('MessageModel');
        $this->app->bind('UserModel');
    }

}