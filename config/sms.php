<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Queue-based SMS processor
    |--------------------------------------------------------------------------
    |
    | When false (default), the sms:process-pending command does not enqueue any
    | jobs. Pending rows stay pending until PUT /api/sms-messages/{id} after your
    | external gateway sends the message. Set true only if you implement real
    | sending inside App\Jobs\ProcessSmsMessage and accept that workers may mutate rows.
    |
    */

    'enqueue_processor_jobs' => filter_var(
        env('SMS_ENQUEUE_PROCESSOR_JOBS', false),
        FILTER_VALIDATE_BOOLEAN
    ),

    /*
    |--------------------------------------------------------------------------
    | Order notification SMS destination
    |--------------------------------------------------------------------------
    |
    | Phone number that receives new-order alerts (pending until your API marks sent).
    |
    */

    'notify_phone' => env('SMS_NOTIFY_PHONE', '+639661841984'),

    /*
    |--------------------------------------------------------------------------
    | Pending list limit (gateway polling)
    |--------------------------------------------------------------------------
    */

    'pending_fetch_limit' => max(1, min(500, (int) env('SMS_PENDING_FETCH_LIMIT', 100))),

];
