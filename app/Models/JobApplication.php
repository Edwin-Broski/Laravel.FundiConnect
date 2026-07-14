<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasUuids;

    protected $fillable = [
        'job_request_id',
        'provider_id',
        'message',
        'status',
    ];

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}