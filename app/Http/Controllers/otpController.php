<?php

namespace App\Http\Controllers;
use App\Models\User;
use Carbon\Carbon;
use App\Models\verificationCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class otpController extends Controller
{
    public function login()
{
    return view('auth.otp-login');
}

public function generate(Request $request)
{
    $request->validate([
        'mobile_no' => 'required|exists:users,mobile_no'
    ]);

    $user = User::where('mobile_no', $request->mobile_no)->first();
    $verificationCode = $this->generateOtp($user);

    return redirect()->route('otp.verification', ['user_id' => $user->id])
        ->with('success', "Your OTP To Login is - ".$verificationCode->otp); 
}

public function generateOtp(User $user)
{
    $verificationCode = VerificationCode::where('user_id', $user->id)
        ->latest()
        ->first();

    if ($verificationCode && $verificationCode->expire_at > Carbon::now()) {
        return $verificationCode;
    }

    return VerificationCode::create([
        'user_id' => $user->id,
        'otp' => rand(123456, 999999),
        'expire_at' => Carbon::now()->addMinutes(10)
    ]);
}

public function verification($user_id)
{
    return view('auth.otp-verification', compact('user_id'));
}

public function loginWithOtp(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'otp' => 'required'
    ]);

    $verificationCode = VerificationCode::where('user_id', $request->user_id)
        ->where('otp', $request->otp)
        ->first();

    if (!$verificationCode) {
        return redirect()->back()->with('error', 'Your OTP is not correct');
    }

    if ($verificationCode->expire_at < Carbon::now()) {
        return redirect()->route('otp.login')->with('error', 'Your OTP has expired');
    }

    $verificationCode->update(['expire_at' => Carbon::now()]);
    $user = User::findOrFail($request->user_id);
    Auth::login($user);

    return redirect('/dashboard');
}

}
