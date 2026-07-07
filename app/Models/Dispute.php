<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    use HasUuids;

    protected $fillable = [
        'job_request_id',
        'raised_by',
        'reason',
        'status',
        'admin_notes',
    ];

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function raisedBy()
    {
        return $this->belongsTo(User::class, 'raised_by');
    }
}