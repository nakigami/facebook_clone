<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RetrieveUsersTest extends TestCase
{
    /**
     * @test
     */
    public function a_user_can_visit_others_profile()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $post = factory(Post::class)->create();

        $response = $this->get('/api/users/'.$user->id);
        $response->assertStatus(200)
            ->assertExactJson([

                'data' => [
                    'type' => 'users',
                    'user_id' => $user->id,
                    'attributes' => [
                        'name' => $user->name
                    ]
                ],
                'links' => [
                    'self' => url('/users/' . $user->id)
                ]
            ]);
    }
}
