<?php /** @noinspection ALL */

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegisterUserMail;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserNotify;
use App\Notifications\EmailVerification;
use App\Notifications\NewLogin;
use App\Providers\RouteServiceProvider;
use Biscolab\ReCaptcha\Facades\ReCaptcha;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\TokenRepository;
use Predis\Command\StringIncrement;
use Predis\Transaction\MultiExec;
use Spatie\FlareClient\Http\Client;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    private $redis;

    public function __construct()
    {
        $this->redis = Redis::connection()->client();
    }

    public function register(Request $request)
    {

        $request->validate([
            'phone' => 'string|max:13',
            'username' => 'required|string|max:80|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8'
        ]);


        $user = new User();
        $user->phone = $request->phone;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $user_id = $user->id;
        $token = Str::random(30);

        $user->notify(new EmailVerification($user_id, $token));

        DB::table('user_notifies')->insert([
            'user_id' => $user_id,
            'token' => $token
        ]);


        return \response()->json([
            'status' => 'ok',
            'data' => $user,
            'error' => null
        ], Response::HTTP_CREATED);
    }

    /*
     *
     * login user
     *
     */

    public function login(Request $request)
    {


        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',

        ]);
        $loginKey = 'login_key_' . $request->email;

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = $request->user();

            $timeout = [
                'timeout' => '10 minutes'
            ];
            $user->notify(new NewLogin($timeout));

            $token = $user->createToken('m-social')->accessToken;

            $this->redis->set($loginKey, 0);
            return response()->json([
                'status' => 'ok',
                'data' => ['token' => $token, 'user' => $user],
                'error' => null,
            ]);
        } else {

            if ($this->redis->get($loginKey) == 3) {

                return redirect()->route('google-captcha');
            }


            $this->redis->incr($loginKey);
            return response()->json([
                'message' => 'email or password ins incorrect'
            ], 400);
        }
    }


    /*
     *
     * logout user
     *
     */

    public function logout(Request $request)
    {
        $logout = $request->user()->token()->revoke();
        if ($logout) {
            return response()->json([
                'message' => 'successful logout'
            ]);
        }
    }
}
