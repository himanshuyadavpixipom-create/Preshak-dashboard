<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'reminder_type',
        'channel',
        'subject',
        'body',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Boot function to enforce single default template per type + channel.
     */
    protected static function booted()
    {
        static::saving(function ($template) {
            if ($template->is_default) {
                static::where('reminder_type', $template->reminder_type)
                    ->where('channel', $template->channel)
                    ->where('id', '!=', $template->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}
