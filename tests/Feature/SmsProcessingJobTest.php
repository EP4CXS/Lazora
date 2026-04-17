<?php

use App\Jobs\ProcessSmsMessage;
use App\Models\SmsMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;

uses(RefreshDatabase::class);

it('dispatches jobs for pending messages via command', function () {
    Bus::fake();

    $pending = SmsMessage::factory()->create(['status' => 'pending']);
    SmsMessage::factory()->create(['status' => 'sent']);

    $this->artisan('sms:process-pending --limit=100')
        ->assertExitCode(0);

    Bus::assertDispatched(ProcessSmsMessage::class, function (ProcessSmsMessage $job) use ($pending) {
        return $job->smsMessageId === $pending->id;
    });
});

it('job marks pending sms as sent', function () {
    $sms = SmsMessage::factory()->create(['status' => 'pending']);

    (new ProcessSmsMessage($sms->id))->handle();

    expect($sms->fresh()->status)->toBe('sent');
});
