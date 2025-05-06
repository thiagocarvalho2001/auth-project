<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    public function showSetup()
    {
        $user = Auth::user();

        if(!$user->google2fa_secret){
            $g = new GoogleAuthenticator();
            $secret = $g->generateSecret();
            $user->google2fa_secret = $secret;
            $user->save();
        }

        $qrText = 'otpauth://totp' . config('app.name') . ':' . $user->email .
            '?secret=' . $user->google2fa_secret .
            '&issuer=' . config('app.name');

        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrText);

        return view('2fa.setup', [
            'qrCode' => $qrCodeSvg,
            'secret' => $user->google2fa_secret,
        ]);
    }

    public function enable2FA(Request $request){
        $request->validate([
            'code' => 'required',
        ]);

        $user = Auth::user();
        $g = new GoogleAuthenticator();

        if($g->checkCode($user->google2fa_secret, $request->code)){
            $user->two_factor_enabled = true;
            $user->save();

            return redirect()->route('dashboard')->with('status', '2FA activated with successfully!');
        }else{
            return back()->withErros(['code' => 'Invalid code']);
        }
    }
}
