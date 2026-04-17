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

        // Delivery is confirmed via PUT /api/sms-messages/{id} after an external gateway
        // sends the message. This job is reserved for optional future work (e.g. retries,
        // provider webhooks) and must not change status here.
    }
}
