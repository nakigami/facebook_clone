<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAuthUserTest extends TestCase
{
    /**
     * @test
     */
    public function a_user_can_fetch_authenticated_user()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create() , 'api');
        $response = $this->get('/api/auth-user');
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'user_id' => $user->id,
                    'attributes' => [
                        'name' => $user->name
                    ]
                ],
                'links' => [
                    'self' => url('/users/'.$user->id)
                ]
            ]);
    }
}
