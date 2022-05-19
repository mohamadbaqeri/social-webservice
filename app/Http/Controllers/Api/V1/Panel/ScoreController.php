<?php

namespace App\Http\Controllers\Api\V1\Panel;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use App\Models\Score;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function Sodium\increment;

class ScoreController extends Controller
{
    public function score(Request $request, $id)
    {













//
//        $request->validate([
//            'score' => 'required|min:1|max:5'
//        ]);
//
//        $post = Post::where('id', $id)->first();
//        if ($post) {
//            $user = $request->user();
//            $score = Score::where('post_id', $post->id)
//                ->where('user_id', $user->id)->first();
//
//
//
//            if ($score) {
//                $score->delete();
//                return \response()->json([
//                    'message' => 'score successfully removed'
//                ]);
//            } else {
//                Score::create([
//                    'score' => $request->score,
//                      'post_id' => $post->id,
//                    'user_id' => $user->id
//                ]);
//                return \response()->json([
//                    'status' => 'ok',
//                    'data' => $post,
//                    'error' => null
//                ], Response::HTTP_OK);
//            }
//        } else {
//            return \response()->json([
//                'status' => 'ok',
//                'message' => 'not found',
//                'error' => null
//            ], Response::HTTP_BAD_REQUEST);
//        }
    }
}
