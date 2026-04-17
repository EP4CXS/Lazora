<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSmsMessageRequest;
use App\Models\SmsMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SmsMessageController extends Controller
{
    /**
     * Pending rows for the gateway to send. The client must call update only after
     * the provider confirms delivery (or failure) on the device/network.
     */
    public function index(Request $request): JsonResponse
    {
        $limit = (int) config('sms.pending_fetch_limit', 100);

        $messages = $this->scopedSmsQuery($request)
            ->where('status', 'pending')
            ->orderBy('id')
            ->limit($limit)
            ->get()
            ->map(fn (SmsMessage $sms) => [
                'id' => $sms->id,
                'phone_number' => $sms->phone_number,
                'message' => $sms->message,
                'status' => $sms->status,
                'created_at' => $sms->created_at,
            ]);

        return response()->json(['data' => $messages]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $smsMessage = $this->scopedSmsQuery($request)
            ->where('id', $id)
            ->firstOrFail();

        return response()->json($smsMessage);
    }

    /**
     * Confirm outbound delivery after the gateway has actually sent (or failed) the SMS.
     */
    public function update(UpdateSmsMessageRequest $request, int $id): JsonResponse
    {
        $sms = $this->scopedSmsQuery($request)
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        $status = $request->validated('status') ?? 'sent';

        $externalId = $request->exists('external_id')
            ? $request->input('external_id')
            : $sms->external_id;

        $sms->forceFill([
            'status' => $status,
            'sent_at' => $status === 'sent' ? now() : null,
            'external_id' => $externalId,
        ])->save();

        return response()->json([
            'id' => $sms->id,
            'phone_number' => $sms->phone_number,
            'message' => $sms->message,
            'status' => $sms->status,
            'sent_at' => $sms->sent_at,
            'external_id' => $sms->external_id,
        ]);
    }

    /**
     * @return Builder<SmsMessage>
     */
    private function scopedSmsQuery(Request $request): Builder
    {
        /** @var User $user */
        $user = $request->user();

        $query = SmsMessage::query();

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        return $query;
    }
}
