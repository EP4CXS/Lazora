<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyPhoneOtpRequest;
use App\Jobs\SendPhoneOtp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class PhoneOtpController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('auth/VerifyPhoneOtp', [
            'phoneNumber' => $request->user()?->phone_number,
        ]);
    }

    public function verify(VerifyPhoneOtpRequest $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->phone_otp_hash || ! $user->phone_otp_expires_at || now()->greaterThan($user->phone_otp_expires_at)) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('OTP expired. Please request a new one.')]);

            return back();
        }

        $user->increment('phone_otp_attempts');

        if (! Hash::check($request->validated('otp'), $user->phone_otp_hash)) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('Invalid OTP code.')]);

            return back();
        }

        $user->forceFill([
            'phone_verified_at' => now(),
            'phone_otp_hash' => null,
            'phone_otp_expires_at' => null,
            'phone_otp_attempts' => 0,
        ])->save();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Phone number verified.')]);

        return redirect()->route('dashboard');
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user || ! $user->phone_number) {
            return back();
        }

        $otp = (string) random_int(100000, 999999);

        $user->forceFill([
            'phone_verified_at' => null,
            'phone_otp_hash' => Hash::make($otp),
            'phone_otp_expires_at' => now()->addMinutes(5),
            'phone_otp_attempts' => 0,
        ])->save();

        SendPhoneOtp::dispatch($user->id, $otp)->onQueue('sms');

        Inertia::flash('toast', ['type' => 'success', 'message' => __('A new OTP has been sent.')]);

        return back();
    }
}
