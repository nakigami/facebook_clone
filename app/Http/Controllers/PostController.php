<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post as PostResource;
use App\Http\Resources\PostCollection;
use App\Post;

class PostController extends Controller
{
    // Retrieve all posts using PostCollection
    public function index()
    {
        return new PostCollection( Post::all() );
    }

    // Store one Post item
    public function store()
    {
        $data = \request()->validate([
            'data.attributes.body' => ''
        ]);

        $post = \request()->user()->posts()->create($data['data']['attributes']);

        return new PostResource( $post );
    }
}
