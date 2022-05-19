<?php

namespace App\Http\Controllers\Api\V1\Panel;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LikeController extends Controller
{
    public function store(Request $request, $id)
    {

        $post = Post::where('id', $id)->first();
        if ($post) {
            $user = $request->user();
            $like = Like::where('post_id', $post->id)
                ->where('user_id', $user->id)->first();
            if ($like) {
                $like->delete();
                return \response()->json([
                    'message' => 'like successfully removed'
                ]);
            } else {
                Like::create([
                    'post_id' => $post->id,
                    'user_id' => $user->id
                ]);
                return \response()->json([
                    'status' => 'ok',
                    'data' => $post,
                    'error' => null
                ], Response::HTTP_OK);
            }
        } else {
            return \response()->json([
                'status' => 'ok',
                'message' => 'not found',
                'error' => null
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}

