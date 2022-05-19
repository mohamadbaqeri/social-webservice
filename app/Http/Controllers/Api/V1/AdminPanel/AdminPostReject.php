<?php

namespace App\Http\Controllers\Api\V1\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPostReject extends Controller
{
    public function reject(Request $request)
    {
        $post = Post::with(['users'])->where('id', $request->post_id)->first();
        $post->load('users:id,username,email,avatar');

        $admin = $request->user();

        if ($admin->role == 'admin') {
            if ($post->status == 'verifying') {
                $post->status = 'rejected';
                $post->save();
            }

            $post_title = $post->title;
            $user_id = $post->user_id;
            $disproof = $request->reason;

            $admin->notify(new \App\Notifications\AdminPostReject($user_id, $post_title, $disproof));

            return response()->json([
                'status' => 'success',
                'data' => $post,
                'error' => 'rejected this post'
            ], Response::HTTP_OK);
        } else {
            return \response()->json([
                'status' => 'failed',
                'data' => null,
                'error' => 'you do not have permission to access this page'
            ], Response::HTTP_FORBIDDEN);
        }

    }
}
