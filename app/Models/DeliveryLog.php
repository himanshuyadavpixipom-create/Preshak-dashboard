<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'reminder_id',
        'template_id',
        'channel',
        'provider',
        'recipient',
        'subject',
        'body',
        'status',
        'provider_message_id',
        'failure_reason',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function reminder()
    {
        return $this->belongsTo(Reminder::class);
    }
}
