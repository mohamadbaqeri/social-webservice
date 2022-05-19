<?php

namespace App\Http\Controllers\Api\V1\Panel;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use App\Models\Skill;
use App\Models\User;
use App\Notifications\AdminPostVerify;
use Dflydev\DotAccessData\Data;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Ramsey\Collection\Collection;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    /*
     *
     * create post ****************************
     *
     */

    public function create(Request $request)
    {
        $validator = $request->validate([
            'title' => 'required|max:30',
            'caption' => 'max:300',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);

        $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->storeAs('postImage', $imageName);

        $user = $request->user();
        $post = new Post();
        if ($user) {
            $post->title = $request->title;
            $post->status = 'verifying';
            $post->caption = $request->caption;
            $post->image = $imageName;
            $user = $user->posts()->save($post);
            $post->load('users:id,username,email');

            return \response()->json([
                'status' => 'ok',
                'data' => $post,
                'error' => null
            ], Response::HTTP_CREATED);
        } else {
            return \response()->json([
                'status' => 'failed',
                'data' => null,
                'error' => 'you are not allowed to create posts'
            ], Response::HTTP_FORBIDDEN);
        }
    }

    /*
     *
     * update posts *****************************
     *
     */


    public function update(Request $request, $id)
    {
        $user = $request->user();
        $post = Post::with(['users'])->where('id', $id)->first();
        if ($post) {
            if ($user->id == $post->user_id || $user->role == 'admin') {

                $validator = $request->validate([
                    'title' => 'required|max:30',
                    'caption' => 'max:300',
                    'image' => 'required|image|mimes:jpg,jpeg,png,gif'
                ]);
                if (!$validator) {
                    return \response()->json([
                        'error' => 'validation error',
                    ], 422);
                }

                if ($request->hasFile('image')) {
                    $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
                    $request->file('image')->storeAs('postImage', $imageName);
                    $old_path = public_path() . 'postImage' . $post->image;
                    if (\Illuminate\Support\Facades\File::exists($old_path)) {
                        \Illuminate\Support\Facades\File::delete($old_path);
                    }
                } else {
                    $imageName = $post->image;
                }

                $post->update([
                    'title' => $request->title,
                    'caption' => $request->caption,
                    'image' => $imageName
                ]);

                return \response()->json([
                    'message' => 'post successfully updated',
                    'data' => $post,
                    'error' => null
                ], Response::HTTP_OK);

            } else {
                return \response()->json([
                    'message' => 'Access denied'
                ], Response::HTTP_FORBIDDEN);
            }
        } else {
            return \response()->json([
                'message' => 'not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /*
     *
     * delete posts ***************************
     *
     */

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        $post = Post::with(['users'])->where('id', $id)->first();
        if ($post) {
            if ($user->id == $post->user_id || $user->role == 'admin') {
                $old_path = public_path() . 'postImage' . $post->image;
                if (\Illuminate\Support\Facades\File::exists($old_path)) {
                    \Illuminate\Support\Facades\File::delete($old_path);
                }

                $post->delete();

                return \response()->json([
                    'message' => 'post successfully deleted',
                ], Response::HTTP_OK);

            } else {
                return \response()->json([
                    'message' => 'Access denied'
                ], Response::HTTP_FORBIDDEN);
            }
        }
    }


    /*
     *
     * search posts **************************
     *
     */


    public function searchPost(Request $request)
    {
        $posts_query = Post::withCount(['likes', 'comments']);

        if ($request->keyword) {
            $posts_query->where('title', 'LIKE', '%' . $request->keyword . '%')
                ->orWhere('caption', 'LIKE', '%' . $request->keyword . '%');
        }


        if ($request->sortBy && in_array($request->sortBy, ['created_at', 'comments_count', 'likes_count'])) {
            $sortBy = $request->sortBy;
        } else {
            $sortBy = 'created_at';
        }

        if ($request->sortOrder && in_array($request->sortOrder, ['asc', 'desc'])) {
            $sortOrder = $request->sortOrder;
        } else {
            $sortOrder = 'desc';
        }


        $posts = $posts_query->orderBy($sortBy, $sortOrder)->get();

        return \response()->json([
            'status' => 'ok',
            'data' => $posts,
            'error' => null
        ], Response::HTTP_OK);
    }
}
