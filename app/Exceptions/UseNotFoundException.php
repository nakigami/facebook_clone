<?php

namespace App\Exceptions;

use Exception;

class UseNotFoundException extends Exception
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
                'title' => 'User not found.',
                'detail' => 'Unable to fetch this user with the given infos.'
            ]
        ], 404);
    }
}
