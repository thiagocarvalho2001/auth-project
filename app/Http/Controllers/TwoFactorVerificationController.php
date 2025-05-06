<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class TwoFactorVerificationController extends Controller
{
    public function showVerifyForm()
    {
        return view('2fa.verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = Auth::user();
        $g = new GoogleAuthenticator();

        if($g->checkCode($user->google2fa_secret, $request->code)){
            $request->session()->put('2fa_passed', true);
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['code' => 'Invalid code']);
    }
}
