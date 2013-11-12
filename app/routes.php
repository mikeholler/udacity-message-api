<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::resource('user', 'UserController', ['only' => ['index', 'show', 'store', 'destroy']]);
Route::resource('user.inbox', 'InboxController', ['only' => ['index', 'show', 'destroy']]);
Route::resource('group', 'GroupController', ['only' => ['index', 'show', 'store', 'destroy']]);
Route::resource('group.member', 'MemberController', ['only' => ['index', 'update', 'destroy']]);
