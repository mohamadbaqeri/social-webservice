<?php

namespace App\Http\Controllers\Api\V1\Panel;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use http\Client\Curl\User;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    public function show(Request $request, $post_id)
    {
        $post = Post::where('id', $post_id)->first();
        if ($post) {
            $paginate = ($request->perPage) ? $request->perPage : 4;
            $comments = Comment::with(['users'])->where('post_id', $post_id)->orderBy('id', 'desc')->paginate($paginate);

            $comments->load('users:id,role,username,avatar');
            return \response()->json([
                'status' => 'ok',
                'data' => $comments,
                'error' => null
            ], Response::HTTP_OK);
        }
    }

    public function create(Request $request, $post_id)
    {
        $user = $request->user();
        $post = Post::where('id', $post_id)->first();
        if ($post) {
            if ($user->id) {
                $request->validate([
                    'comment' => 'required|max:1000'
                ]);
                $comment = Comment::create([
                    'comment' => $request->comment,
                    'post_id' => $post->id,
                    'user_id' => $request->user()->id
                ]);


                return \response()->json([
                    'status' => 'ok',
                    'data' => $comment,
                    'error' => null,
                ], Response::HTTP_CREATED);
            } else {
                return \response()->json([
                    'message' => 'not found'
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    public function update(Request $request, $comment_id)
    {
        $user = $request->user();
        $comment = Comment::with(['users', 'posts'])->where('id', $comment_id)->first();
        $comment->load('users:id,role,username');
        if ($comment) {
            if ($user->id === $comment->user_id || $user->role == 'admin') {
                $comment->update([
                    'comment' => $request->comment,
                ]);
                return \response()->json([
                    'message' => 'successfully updated',
                    'data' => $comment,
                    'error' => null
                ], Response::HTTP_CREATED);
            } else {
                return \response()->json([
                    'message' => 'access denied'
                ], 403);
            }
        } else {
            return \response()->json([
                'message' => 'not found'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(Request $request, $comment_id)
    {
        $user = $request->user();
        $comment = Comment::where('id', $comment_id)->first();
        if ($comment) {
            if ($user->id === $comment->user_id || $user->role == 'admin ') {
                $comment->delete();
                return \response()->json([
                    'message' => 'comment successfully deleted'
                ], Response::HTTP_OK);
            } else {
                return \response()->json([
                    'message' => 'Access denied'
                ], 403);
            }
        } else {
            return \response()->json([
                'message' => 'no comment found'
            ], 400);
        }
    }
}
