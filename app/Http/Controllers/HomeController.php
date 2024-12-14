<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    function totpEnable(Request $request): JsonResponse
    {

        $user = auth()->user();
        if ($request->totp_enabled) {
            if (!$user->totp_secret) {
                $google2fa = new Google2FA();
                $secret = $google2fa->generateSecretKey();
                $qrCodeUrl = $google2fa->getQRCodeUrl(
                    config('app.name'),
                    $user->email,
                    $secret
                );
                $user->totp_secret = $secret;
            }
        }
        $user->totp_enabled = $request->totp_enabled;
        $user->save();
        return response()->json([
            'status' => true,
            'data' => [
                'qr' => $qrCodeUrl ?? '',
                'name' => config('app.name') . ' - ' . $user->email,
                'secret' => $user->totp_secret
            ]
        ]);
    }
}
