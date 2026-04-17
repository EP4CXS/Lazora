<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsMessage extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (SmsMessage $sms): void {
            if ($sms->status === null || $sms->status === '') {
                $sms->status = 'pending';
            }
        });
    }

    protected $fillable = [
        'user_id',
        'phone_number',
        'message',
        'status',
        'sent_at',
        'external_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
