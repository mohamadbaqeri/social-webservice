<?php

namespace App\Http\Controllers\Api\V1\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class ShowDetailController extends Controller
{
    public function showUsers(Request $request)
    {
        $admin = $request->user();
        $user = User::all();

        if ($admin->role == 'admin') {
            return response()->json([
                'status' => 'success',
                'data' => $user,
                'error' => null,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'error' => 'you do not have permission to access this page'
            ], Response::HTTP_FORBIDDEN);
        }
    }

    public function showPosts(Request $request, $user_id)
    {
        $admin = $request->user();
        $user = User::with(['posts'])->where('id', $user_id)->get(['id', 'username', 'avatar']);
        $user->load('posts');

        if ($admin->role == 'admin') {
            return \response()->json([
                'status' => 'success',
                'data' => $user,
                'error' => null,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'error' => 'you do not have permission to access this page'
            ], Response::HTTP_FORBIDDEN);
        }
    }
}
