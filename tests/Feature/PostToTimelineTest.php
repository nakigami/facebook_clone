<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Tests\TestCase;

class PostToTimelineTest extends TestCase
{

    /**
     * @test
     */
    public function a_user_can_post_a_text_post()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(),'api');

        // Creating a new Post
        $response = $this->post('/api/posts' , [
            'data' => [
                'type' => 'posts',
                'attributes' => [
                    'body' => 'Testing Body response'
                ]
            ]
        ]);

        // Get the post
        $post = Post::first();
        $this->assertCount(1, Post::all());

        $this->assertEquals($user->id , $post->user_id);
        $this->assertEquals('Testing Body response' , $post->body);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'type' => 'posts',
                    'post_id' => $post->id,
                    'attributes' => [
                        'posted_by' => [
                            'data' => [
                                'attributes' => [
                                    'name' => $user->name
                                ]
                            ]
                        ],
                        'body' => 'Testing Body response'
                    ]
                ],
                'links' => [
                    'self' => url('/posts/'.$post->id)
                ]
            ]);
    }
}
