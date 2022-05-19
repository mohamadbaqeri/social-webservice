<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class VerifyEmailController extends Controller
{

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect('/home');
    }

    public function notify(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    }
}



























//    use VerifiesEmails;
//
//
//    /**
//     * @var string
//     */
//    protected $redirectTo = '/home';
//
//
//    /**
//     * @return void
//     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//        $this->middleware('signed')->only('verify');
//        $this->middleware( 'throttle:6,1')->only('verify','resend');
//    }
//
//
//
//
//    use RedirectsUsers;
//
//    /**
//     * Show the email verification notice.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
//     */
//    public function show(Request $request)
//    {
//        return $request->user()->hasVerifiedEmail()
//            ? redirect($this->redirectPath())
//            : view('auth.verify');
//    }
//
//    /**
//     * Mark the authenticated user's email address as verified.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
//     *
//     * @throws \Illuminate\Auth\Access\AuthorizationException
//     */
//    public function verify(Request $request)
//    {
//        if (! hash_equals((string) $request->route('id'), (string) $request->user()->getKey())) {
//            throw new AuthorizationException;
//        }
//
//        if (! hash_equals((string) $request->route('hash'), sha1($request->user()->getEmailForVerification()))) {
//            throw new AuthorizationException;
//        }
//
//        if ($request->user()->hasVerifiedEmail()) {
//            return $request->wantsJson()
//                ? new JsonResponse([], 204)
//                : redirect($this->redirectPath());
//        }
//
//        if ($request->user()->markEmailAsVerified()) {
//            event(new Verified($request->user()));
//        }
//
//        if ($response = $this->verified($request)) {
//            return $response;
//        }
//
//        return $request->wantsJson()
//            ? new JsonResponse([], 204)
//            : redirect($this->redirectPath())->with('verified', true);
//    }
//
//    /**
//     * The user has been verified.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return mixed
//     */
//    protected function verified(Request $request)
//    {
//        //
//    }
//
//    /**
//     * Resend the email verification notification.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
//     */
//    public function resend(Request $request)
//    {
//        if ($request->user()->hasVerifiedEmail()) {
//            return $request->wantsJson()
//                ? new JsonResponse([], 204)
//                : redirect($this->redirectPath());
//        }
//
//        $request->user()->sendEmailVerificationNotification();
//
//        return $request->wantsJson()
//            ? new JsonResponse([], 202)
//            : back()->with('resent', true);
//    }
