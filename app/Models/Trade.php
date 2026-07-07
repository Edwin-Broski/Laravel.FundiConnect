<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'icon'];

    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'provider_trades');
    }

    public function jobRequests()
    {
        return $this->hasMany(JobRequest::class);
    }
}