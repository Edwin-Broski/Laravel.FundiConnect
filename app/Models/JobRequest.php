<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class JobRequest extends Model
{
    use HasUuids;

    protected $fillable = [
        'customer_id',
        'provider_id',
        'trade_id',
        'description',
        'location_address',
        'location_area',
        'status',
        'customer_confirmed',
        'provider_confirmed',
        'completion_photo',
        'customer_no_show_flag',
        'scheduled_at',
    ];

    protected $casts = [
        'customer_confirmed'    => 'boolean',
        'provider_confirmed'    => 'boolean',
        'customer_no_show_flag' => 'boolean',
        'scheduled_at'          => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function trade()
    {
        return $this->belongsTo(Trade::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function dispute()
    {
        return $this->hasOne(Dispute::class);
    }

    // status helpers
    public function isPending(): bool    { return $this->status === 'pending'; }
    public function isAccepted(): bool   { return $this->status === 'accepted'; }
    public function isCompleted(): bool  { return $this->status === 'completed'; }
    public function isCancelled(): bool  { return $this->status === 'cancelled'; }
}