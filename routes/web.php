<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('email-verification', function (Request $request) {
    $exists = \Illuminate\Support\Facades\DB::table('user_notifies')
        ->where('user_id', '=', $request->id)
        ->where('token', '=', $request->token)
        ->exists();

    if ($exists) {
        $user = User::find($request->id);
        $user->email_verified_at = now();
        $user->save();
    } else {
        return "not exists";
    }
});


Route::get('/social-image', function (Request $request) {
});




