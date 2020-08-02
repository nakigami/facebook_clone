<?php

namespace App\Http\Controllers;

use App\Exceptions\FriendRequestNotFoundExcpetion;
use App\Friend;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FriendRequestResponseController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'user_id' => 'required',
            'status' => 'required'
        ]);

        try{
            $friendRequest = Friend::where('user_id', $data['user_id'])
                ->where('friend_id', auth()->user()->id)
                ->firstOrFail();
        }catch(ModelNotFoundException $e){
            throw new FriendRequestNotFoundExcpetion();
        }

        $friendRequest->update(array_merge($data, [
            'confirmed_at' => now()
        ]));

        return new \App\Http\Resources\Friend($friendRequest);
    }
}
