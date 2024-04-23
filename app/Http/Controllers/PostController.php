<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function create(Request $request){
        //Pahamin Sintaks
        $request->validate([
            'caption' => 'required',
            'attachments' => 'required|array',
            'attachments.*' => 'required|image|mimes:jpg,jpeg,webp,png,gif',
        ]);
        
        $post = new Post();
        $post->caption = $request->caption;
        $post->user_id = Auth::user()->id;
        $post->save();
        
        //Ambil foto
        $img = $request->file('attachments');
        foreach($img as $im){
            $name = $im->hashName(); //Ambil Nama Foto yang sudah di Hash
            $im->storeAs('public/posts', $name); //Masuk Ke Storage

            $attachments = new PostAttachment();
            $attachments->storage_path = "posts/".$name; //Nama File (jpg)
            $attachments->post_id = $post->id;
            $attachments->save();

        }

        return response()->json([
            'message' => 'Create post success'
        ], 201);
    }

    public function delete(Request $request, $id){
        $posts = Post::where('id', $id)->value('user_id');
        if(Auth::user()->id == $posts){
            $attachment = PostAttachment::where('post_id', $id);
            $attachment->delete();
    
            $post = Post::where('id', $id);
            $post->delete();    
            return response()->json([], 204);

        } else if($posts == null) {
            return response()->json(['message' => 'Post not found'], 404);

        } else{
            return response()->json([
                'message' => 'Forbidden acecss'
            ], 403);
        }
    }

    public function getAll(Request $request){
        if($request->page != null && $request->size != null){
            $request->validate([
                'page' => 'integer|min:0',
                'size' => 'integer|min:1'
            ]);
        }

        if($request->page == null){
            $page = 0;
        }  else {
            $page = $request->page;
        }

        if($request->size == null){
            $size = 10;
        } else {
            $size = $request->size;
        }

        return response()->json([
            'page' => $page,
            'size' => $size,
            'posts' => Post::with(['users', 'attachments'])->paginate($size, '*', 'page', $page+1)
        ]);
    }
}
