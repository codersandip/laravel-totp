<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'loginTotp', 'loginTotpVerify']);
        $this->middleware('auth')->only(['logout', 'loginTotp', 'loginTotpVerify']);
    }

    public function loginTotp()
    {
        return view('auth.login-totp');
    }

    public function loginTotpVerify(Request $request) /* : \Illuminate\Http\RedirectResponse */
    {
        $google2fa = new Google2FA();
        $user = auth()->user();
        if ($google2fa->verifyKey($user->totp_secret, $request->totp)) {
            return redirect()->intended('home')->withCookie(cookie('totp', encrypt($user->id), round(now()->diffInMinutes(now()->endOfDay()))));
        } else {
            return back()->withErrors(['totp' => 'Invalid TOTP code']);
        }
    }
}
