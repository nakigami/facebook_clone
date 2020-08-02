<?php

namespace Tests\Feature;

use App\Friend;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FriendsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @test
     */
    public function a_user_can_send_a_friend_request()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $friend = factory(User::class)->create();

        $response = $this->post('/api/friend-request', [
            'friend_id' => $friend->id
        ])->assertStatus(200);

        $friendRequest = Friend::first();
        $this->assertNotNull($friendRequest);
        $this->assertEquals($friend->id, $friendRequest->friend_id);
        $this->assertEquals($user->id, $friendRequest->user_id);

        $response->assertJson([
            'data' => [
                'type' => 'friend-request',
                'friend_request_id' => $friendRequest->id,
                'attributes' => [
                    'confirmed_at' => null,
                ],
            'links' => [
                'self' => url('/users/'.$friend->id)
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function only_valid_users_can_be_requested()
    {
        $this->actingAs($user = factory(User::class)->create(),'api');

        $response = $this->post('/api/friend-request', [
            'friend_id' => 1542
        ])->assertStatus(404);

        $this->assertNull(Friend::first());

        $response->assertJson([
            'errors' => [
                'status' => 404,
                'title' => 'User not found.',
                'detail' => 'Unable to fetch this user with the given infos.'
            ]
        ]);
    }

    /**
     * @test
     */
    public function friend_request_can_be_accepted()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $friend = factory(User::class)->create();

        $this->post('/api/friend-request', [
            'friend_id' => $friend->id
        ])->assertStatus(200);

        $response = $this->actingAs($friend, 'api')
        ->post('/api/friend-request-response', [
            'user_id' => $user->id,
            'status' => 1
        ])->assertStatus(200);

        $friendRequest = Friend::first();
        $this->assertNotNull($friendRequest->confirmed_at);
        $this->assertInstanceOf(Carbon::class, $friendRequest->confirmed_at);

        $this->assertEquals(now()->startOfSecond(), $friendRequest->confirmed_at);
        $this->assertEquals(1, $friendRequest->status);
        $response->assertJson([
            'data' => [
                'type' => 'friend-request',
                'friend_request_id' => $friendRequest->id,
                'attributes' => [
                    'confirmed_at' => $friendRequest->confirmed_at->diffForHumans(),
                ],
                'links' => [
                    'self' => url('/users/'.$friend->id)
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function only_valid_friend_request_can_be_accepted()
    {
        $friend = factory(User::class)->create();
        $response = $this->actingAs($friend, 'api')
            ->post('/api/friend-request-response', [
                'user_id' => 123,
                'status' => 1
            ])->assertStatus(404);

        $this->assertNull(Friend::first());
        $response->assertJson([
            'errors' => [
                'status' => 404,
                'title' => 'Friend Request not found.',
                'detail' => 'Unable to fetch this friend request with the given infos.'
            ]
        ]);
    }

    /**
     * @test
     */
    public function only_recipient_can_accept_a_friend_request()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $friend = factory(User::class)->create();

        $this->post('/api/friend-request', [
            'friend_id' => $friend->id
        ])->assertStatus(200);

        $response = $this->actingAs(factory(User::class)->create(), 'api')
            ->post('/api/friend-request-response', [
                'user_id' => $user->id,
                'status' => 1
            ])->assertStatus(404);

        $friendRequest = Friend::first();
        $this->assertNull($friendRequest->confirmed_at);
        $this->assertNull($friendRequest->status);
        $response->assertJson([
            'errors' => [
                'status' => 404,
                'title' => 'Friend Request not found.',
                'detail' => 'Unable to fetch this friend request with the given infos.'
            ]
        ]);
    }

    /**
     * @test
     */
    public function a_friend_id_is_required_for_friend_request()
    {
       // $this->withoutExceptionHandling();
        $response = $this->actingAs($user = factory(User::class)->create(), 'api')
                    ->post('/api/friend-request', [
                        'friend_id' => ''
                    ])->assertStatus(422);

        $responseString = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('friend_id', $responseString['errors']['meta']);
    }

    /**
     * @test
     */
    public function a_user_id_and_status_are_required_for_friend_request_response()
    {
        $response = $this->actingAs(factory(User::class)->create(), 'api')
            ->post('/api/friend-request-response', [
                'user_id' => '',
                'status' => ''
            ])->assertStatus(422);
        $responseString = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('user_id', $responseString['errors']['meta']);
        $this->assertArrayHasKey('status', $responseString['errors']['meta']);
    }
}
