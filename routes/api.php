<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function(){
    Route::get('auth-user' , 'UserAuthController@show');
    Route::apiResources([
        '/posts' => 'PostController',
        '/users' => 'UserController',
        '/users/{user}/posts' => 'UserPostsController',
        'friend-request' => 'FriendRequestController'
    ]);

});
