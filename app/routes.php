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

// Verify the given user actually exists.
Route::bind('users', function ($userId, $route)
{
    $userModel = App::make('UserModel');
    $user = $userModel->getOne($userId);

    if ($user)
    {
        return $userId;
    }
    else
    {
        return null;
    }
});

// Verify the given group actually exists.
Route::bind('groups', function ($groupId, $route)
{
    $groupModel = App::make('GroupModel');
    $group = $groupModel->getOne($groupId);

    if ($group)
    {
        return $groupId;
    }
    else
    {
        return null;
    }
});

// Verify the a given member is actually a user.
Route::bind('members', function ($memberId, $route)
{
    $userModel = App::make('UserModel');
    $user = $userModel->getOne($memberId);

    if ($user)
    {
        return $memberId;
    }
    else
    {
        return null;
    }
});

Route::resource('users', 'UserController', ['only' => ['index', 'show', 'store', 'destroy']]);
Route::get('users/{users}/groups', 'UserController@showGroups');
Route::post('users/{users}/send', 'UserController@sendMessage');
Route::resource('users.inbox', 'InboxController', ['only' => ['index', 'show', 'destroy']]);
Route::resource('groups', 'GroupController', ['only' => ['index', 'show', 'store', 'destroy']]);
Route::resource('groups.members', 'MemberController', ['only' => ['index', 'update', 'destroy']]);
