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
     *
     * Does not change sms_messages.status. External integrations poll GET /api/sms-messages
     * and mark rows sent with PUT /api/sms-messages/{id}. Enable SMS_ENQUEUE_PROCESSOR_JOBS and
     * implement a real provider here only if you want the queue to drive sending.
     */
    public function handle(): void
    {
        if (! config('sms.enqueue_processor_jobs')) {
            return;
        }

        $sms = SmsMessage::query()->find($this->smsMessageId);

        if (! $sms || $sms->status !== 'pending') {
            return;
        }

        // Implement SMS provider integration here when enqueue_processor_jobs is enabled.
    }
}
