<?php

namespace Tests\Feature;

use App\Http\Resources\User as UserResource;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCanViewProfileTest extends TestCase
{
    /**
     * @test
     */
    public function a_user_can_view_profile_posts()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(),'api');
        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $response = $this->get("/api/users/" . $user->id. '/posts');
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'data' => [
                                'type' => 'posts',
                                'post_id' => $post->id,
                                'attributes' => [
                                    'posted_by' => [
                                        'data' => [
                                            'attributes' =>[
                                                'name' => $user->name
                                            ]
                                        ],
                                        'links' => [
                                            'self' => url('/users/'.$user->id)
                                        ]
                                    ],
                                    'body' => $post->body,
                                    'posted_at' => $post->created_at->diffForHumans(),
                                    'image' => $post->image,
                                ]
                        ],
                        'links' => [
                            'self' => url('/posts/'.$post->id)
                        ]
                    ]
                ],
                'links' => [
                    'self' => url('/posts')
                ]
            ]);
    }
}
