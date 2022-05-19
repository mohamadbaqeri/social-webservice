<?php

namespace App\Http\Controllers\Api\V1\Panel;

use App\Http\Controllers\Controller;
use App\Models\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;


class SinglePostController extends Controller
{
    public function singlePost(Request $request, $post_id)
    {

        $user = $request->user();
        $post = Post::with(['users', 'likes', 'comments'])->where('id', $post_id)->first();
        $post->load('users:id,role,username,avatar', 'likes', 'comments');


        if ($user->id === $post->user_id || $user->role == 'admin') {
            return response()->json([
                'status' => 'ok',
                'data' => $post,
                'error' => null
            ], Response::HTTP_OK);
        } else {
            return \response()->json([
                'status' => 'ok',
                'data' => null,
                'error' => 'Access denied'
            ], Response::HTTP_FORBIDDEN);
        }


    }
}
