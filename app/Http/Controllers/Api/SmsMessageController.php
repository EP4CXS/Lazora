<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SmsMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SmsMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $messages = SmsMessage::query()

            ->where('status', 'pending')
            ->latest()
            ->get();

        return response()->json(['data' => $messages]);
    }

    /**
     * Display the specified resource.
     */
    public function show(SmsMessage $smsMessage, Request $request): JsonResponse
    {
        abort_unless($smsMessage->user_id === $request->user()->id, 404);

        return response()->json($smsMessage);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SmsMessage $sms): JsonResponse
    {
        $sms->update(['status' => 'sent']);
        $sms->save();

        return response()->json($sms);
    }
}
