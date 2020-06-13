<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\User;
use Illuminate\Http\Request;

class UserPostsController extends Controller
{
    public function index(User $user )
    {
        return new PostCollection($user->posts);
    }
}
