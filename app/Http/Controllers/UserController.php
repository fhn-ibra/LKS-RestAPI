<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function all(Request $request){
        $data = User::whereNot('username', $request->user()->username)->get();

        return response()->json([
            'users' => $data
        ]);
    }

    public function username(Request $request, $username){
        $data = User::where('username', $username)->first();
        
        if($data == null){
            return response()->json([
                'message' => 'User Not Found'
            ], 404);
        }

        return response()->json([
            'id' => $data->id,
            'full_name' => $data->full_name,
            'username' => $data->username,
            'bio' => $data->bio,
            'is_private' => $data->is_private,
            'created_at' => $data->created_at,
            'is_your_account' => (Auth::user()->id == $data->id ? true : false),
            'posts_count' => Post::where('user_id', $data->id)->count(),
            'posts' => Post::where('user_id', $data->id)->with('attachments')->get()
        ]);
    }
}
