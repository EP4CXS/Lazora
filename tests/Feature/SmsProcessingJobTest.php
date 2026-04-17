<?php

use App\Jobs\ProcessSmsMessage;
use App\Models\SmsMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;

uses(RefreshDatabase::class);

it('does not dispatch processor jobs by default', function () {
    Bus::fake();

    SmsMessage::factory()->create(['status' => 'pending']);

    $this->artisan('sms:process-pending --limit=100')
        ->assertExitCode(0);

    Bus::assertNothingDispatched();
});

it('dispatches jobs when sms enqueue processor is enabled', function () {
    Bus::fake();
    Config::set('sms.enqueue_processor_jobs', true);

    $pending = SmsMessage::factory()->create(['status' => 'pending']);
    SmsMessage::factory()->create(['status' => 'sent']);

    $this->artisan('sms:process-pending --limit=100')
        ->assertExitCode(0);

    Bus::assertDispatched(ProcessSmsMessage::class, function (ProcessSmsMessage $job) use ($pending) {
        return $job->smsMessageId === $pending->id;
    });
});

it('job does not mark sms as sent when processor enqueue is disabled', function () {
    Config::set('sms.enqueue_processor_jobs', false);

    $sms = SmsMessage::factory()->create(['status' => 'pending']);

    (new ProcessSmsMessage($sms->id))->handle();

    expect($sms->fresh()->status)->toBe('pending');
});

it('job does not mark sms as sent when processor enqueue is enabled but provider is not implemented', function () {
    Config::set('sms.enqueue_processor_jobs', true);

    $sms = SmsMessage::factory()->create(['status' => 'pending']);

    (new ProcessSmsMessage($sms->id))->handle();

    expect($sms->fresh()->status)->toBe('pending');
});
