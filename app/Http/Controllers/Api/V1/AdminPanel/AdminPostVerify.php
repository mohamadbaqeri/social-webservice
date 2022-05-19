<?php

namespace App\Http\Controllers\Api\V1\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPostVerify extends Controller
{
    public function verify(Request $request)
    {
        $admin = $request->user();

        $posts = Post::with(['users'])->where('id', $request->post_id)->first();
        $posts->load('users:id,username,email,avatar');

        if ($admin->role == 'admin') {
            if ($posts->status = 'verifying') {
                $posts->status = 'verified';
                $posts->save();

                return response()->json([
                    'status' => 'success',
                    'data' => $posts,
                    'error' => null
                ], Response::HTTP_CREATED);
            }
        } else {
            return \response()->json([
                'status' => 'failed',
                'data' => null,
                'error' => 'you do not have permission to access this page'
            ], Response::HTTP_FORBIDDEN);
        }
    }
}
