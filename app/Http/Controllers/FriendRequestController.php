<?php

namespace App\Http\Controllers;

use App\Exceptions\UseNotFoundException;
use App\Exceptions\ValidationErrorException;
use App\Friend;
use App\Http\Resources\Friend as FriendResource;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class FriendRequestController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'friend_id' => 'required'
        ]);

        // Attach received friend to the authenticated user
        try{
            $user = User::findOrFail($data['friend_id'])
                ->friends()
                ->attach(auth()->user());
        }catch (ModelNotFoundException $e){
            throw new UseNotFoundException();
        }

        return new FriendResource(
            Friend::where('user_id', auth()->user()->id)
            ->where('friend_id', $data['friend_id'])
            ->first()
        );
    }
}
