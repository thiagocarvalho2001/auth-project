<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use app\Http\Controllers\ProfileController;
use illuminate\Support\Facades\Auth;

class Ensure2FAIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if($user->two_factr_enabled && !$request->session()->get('2fa_passed')){
            return redirect()->route('2fa.verify');
        }

        return $next($request);
    }
}
