<?php

namespace App\Http\Controllers\Api\V1\Panel;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Skill;
use App\Notifications\EmailVerification;
use App\Notifications\NewLogin;
use App\Models\User;
use App\Providers\AuthServiceProvider;
use App\Policies\UserPolicy;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\Image;
use Psy\Util\Str;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\HasPermissionsTraits;

class UserController extends Controller
{

    public function update(Request $request)
    {

        $validator = $request->validate([
            'firstname' => 'required|max:50',
            'lastname' => 'required|max:50',
            'avatar' => 'image|mimes:jpg,jpeg,png,gif',
            'about' => 'required|min:80',
            'username' => 'max:80|unique:users',
        ]);


        $imageName = time() . '.' . $request->file('avatar')->getClientOriginalExtension();
        $request->file('avatar')->storeAs('avatar/', $imageName);


        $user = $request->user();
        if ($user) {
            if ($validator) {
                $user->firstname = $request->firstname;
                $user->lastname = $request->lastname;
                $user->about = $request->about;
                $user->avatar = $imageName;
                $user->username = $request->username;
                $user->save();

                return \response()->json([
                    'status' => 'ok',
                    'data' => $user,
                    'error' => null
                ], Response::HTTP_CREATED);

            } else {
                return back()->withErrors([
                    'message' => 'please complete the information'
                ]);
            }
        }
    }


    /*
     *
     * start skill
     *
     */

    public function addSkill(Request $request)
    {
        $request->validate([
            'skills' => 'required|array|min:3,max:10'
        ]);
        $user = $request->user();
        $array = array();
        foreach ($request->skills as $item) {
            $skill = $item['name'];
            $id = $this->returnSkills($skill);
            array_push($array, $id);
            $user->save();
        }
        $user->skills()->sync($array);
        return \response()->json([
            'status' => 'ok',
            'data' => $id,
            'error' => null
        ], Response::HTTP_OK);
    }

    private function returnSkills($skillName)
    {
        $skill = Skill::where('skill', $skillName)->first();
        if ($skill != null) {
            return \response()->json([
                'status' => 'ok',
                'data' => $skill->id,
                'error' => null
            ]);
        } else {
            $skill = Skill::create(['skill' => $skillName]);
            return \response()->json([
                'status' => 'ok',
                'data' => $skill->id,
                'error' => null
            ]);
        }
    }

    /*
     *
     * end skill
     *
     */


    /*
     *
     * start show info
     *
     */

    public function information()
    {
        $user = Auth::user();
        $allUser = User::all();

        if ($user->role == 'admin') {
            return \response()->json([
                'status' => 'ok',
                'data' => $allUser,
                'error' => null
            ], Response::HTTP_OK);
        } elseif ($user->role == null) {
            return \response()->json([
                'status' => 'ok',
                'data' => $user,
                'error' => null
            ], Response::HTTP_OK);
        } else {
            abort(403);
        }
    }

    public function guest($id)
    {
        $user = User::find($id);
        return \response()->json([
            'status' => 'ok',
            'data' => $user->only(['username', 'about', 'avatar']),
            'error' => null
        ], Response::HTTP_OK);
    }
}
