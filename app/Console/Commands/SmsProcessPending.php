<?php

namespace App\Console\Commands;

use App\Jobs\ProcessSmsMessage;
use App\Models\SmsMessage;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('sms:process-pending {--limit=100}')]
#[Description('Report pending SMS count; optionally dispatch processor jobs when SMS_ENQUEUE_PROCESSOR_JOBS=true')]
class SmsProcessPending extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $pendingCount = SmsMessage::query()->where('status', 'pending')->count();

        if (! config('sms.enqueue_processor_jobs')) {
            $this->info("Pending SMS messages: {$pendingCount}. Processor jobs are disabled (SMS_ENQUEUE_PROCESSOR_JOBS=false). Confirm delivery with PUT /api/sms-messages/{id}.");

            return self::SUCCESS;
        }

        $limit = (int) $this->option('limit');

        $ids = SmsMessage::query()
            ->where('status', 'pending')
            ->orderBy('id')
            ->limit($limit)
            ->pluck('id');

        foreach ($ids as $id) {
            ProcessSmsMessage::dispatch((int) $id)->onQueue('sms');
        }

        $this->info("Dispatched {$ids->count()} SMS processor job(s). Pending in database before run: {$pendingCount}.");

        return self::SUCCESS;
    }
}
