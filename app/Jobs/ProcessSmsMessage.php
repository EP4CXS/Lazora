<?php

namespace App\Jobs;

use App\Models\SmsMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessSmsMessage implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $smsMessageId) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $sms = SmsMessage::query()->find($this->smsMessageId);

        if (! $sms) {
            return;
        }

        if ($sms->status !== 'pending') {
            return;
        }

        // Placeholder "processing": mark as sent.
        // Replace this with an actual SMS gateway call.
        $sms->forceFill(['status' => 'sent'])->save();
    }
}
