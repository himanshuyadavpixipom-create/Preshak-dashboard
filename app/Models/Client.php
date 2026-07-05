<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'whatsapp_number',
        'birthday',
        'anniversary_date',
        'premium_due_date',
        'policy_name',
        'policy_number',
        'company_name',
        'address',
        'notes',
        'status',
    ];

    protected $casts = [
        'birthday' => 'date',
        'anniversary_date' => 'date',
        'premium_due_date' => 'date',
    ];

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    /**
     * Ensure empty strings are converted to null for the unique policy_number field.
     */
    protected function policyNumber(): Attribute
    {
        return Attribute::make(
            set: fn (string|null $value) => empty(trim($value)) ? null : trim($value),
        );
    }
}
