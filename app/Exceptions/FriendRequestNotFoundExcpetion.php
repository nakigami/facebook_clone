<?php

namespace App\Exceptions;

use Exception;

class FriendRequestNotFoundExcpetion extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json([
            'errors' => [
                'status' => 404,
                'title' => 'Friend Request not found.',
                'detail' => 'Unable to fetch this friend request with the given infos.'
            ]
        ], 404);
    }
}
