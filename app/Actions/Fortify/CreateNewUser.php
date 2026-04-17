<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Jobs\SendPhoneOtp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone_number' => $input['phone_number'] ?? null,
            'password' => $input['password'],
            'role' => 'customer',
        ]);

        if ($user->phone_number) {
            $otp = (string) random_int(100000, 999999);

            $user->forceFill([
                'phone_verified_at' => null,
                'phone_otp_hash' => Hash::make($otp),
                'phone_otp_expires_at' => now()->addMinutes(5),
                'phone_otp_attempts' => 0,
            ])->save();

            SendPhoneOtp::dispatch($user->id, $otp)->onQueue('sms');
        }

        return $user;
    }
}
