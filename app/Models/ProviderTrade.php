<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProviderTrade extends Model
{
    use HasUuids;

    protected $fillable = ['provider_id', 'trade_id'];
}