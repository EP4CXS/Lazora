<?php

namespace App\Console\Commands;

use App\Jobs\ProcessSmsMessage;
use App\Models\SmsMessage;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('sms:process-pending {--limit=100}')]
#[Description('Dispatch queue jobs for pending SMS (does not mark sent; use the SMS API PUT after external send)')]
class SmsProcessPending extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $limit = (int) $this->option('limit');

        $ids = SmsMessage::query()
            ->where('status', 'pending')
            ->orderBy('id')
            ->limit($limit)
            ->pluck('id');

        foreach ($ids as $id) {
            ProcessSmsMessage::dispatch((int) $id)->onQueue('sms');
        }

        $this->info("Dispatched {$ids->count()} SMS job(s).");

        return self::SUCCESS;
    }
}
