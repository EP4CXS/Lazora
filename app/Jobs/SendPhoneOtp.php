<?php

namespace App\Jobs;

use App\Models\SmsMessage;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendPhoneOtp implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $userId,
        public string $otp,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::query()->find($this->userId);

        if (! $user || ! $user->phone_number) {
            return;
        }

        SmsMessage::query()->create([
            'user_id' => $user->id,
            'phone_number' => $user->phone_number,
            'message' => "Your verification code is {$this->otp}. It expires in 5 minutes.",
            'status' => 'pending',
        ]);
    }
}
