<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'message',
        'type',
        'status',
        'queue_id',
        'sent_by',
        'error_message',
        'message_sid'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    /**
     * Scopes
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeQueueAlerts($query)
    {
        return $query->where('type', 'queue_alert');
    }

    public function scopeCallNotifications($query)
    {
        return $query->where('type', 'call_notification');
    }

    public function scopeSuccessful($query)
    {
        return $query->whereIn('status', ['sent', 'delivered']);
    }
}