<?php

namespace App\Http\Controllers\Api\V1\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminLoginController extends Controller
{
    public function adminLogin(Request $request)
    {
        $user = $request->user();

        if ($user->role == 'admin') {
            $validator = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:8'
            ]);

            if ($validator) {
                return \response()->json([
                    'status' => 'ok',
                    'message' => 'successful login',
                    'error' => null
                ], Response::HTTP_OK);
            } else {
                return \response()->json([
                    'your email or password is incorrect!'
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json([
                'error' => 'you do not have permission to access this page'
            ], Response::HTTP_FORBIDDEN);

        }
    }

    /*
     *
     * admin login instead user
     *
     */

    public function loginUser(Request $request, $user_id)
    {
        $admin = $request->user();
        $user = User::find($user_id);

        if ($admin->role == 'admin') {
            $token = $user->createToken('user_token')->accessToken;
            return \response()->json([
                'status' => 'success',
                'data' => $token,
                'user' => $user,
                'error' => null,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'error' => 'you do not have permission to access this page'
            ], Response::HTTP_FORBIDDEN);
        }
    }


}
