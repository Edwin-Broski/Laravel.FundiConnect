<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'bio',
        'location_area',
        'location_district',
        'is_available',
        'is_verified',
        'avg_rating',
        'jobs_completed',
        'id_photo',
        'certificate_photo',
        'status',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_verified'  => 'boolean',
        'avg_rating'   => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trades()
    {
        return $this->belongsToMany(Trade::class, 'provider_trades');
    }

    public function jobRequests()
    {
        return $this->hasMany(JobRequest::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // recalculates and saves avg_rating after every new review
    public function updateRating(): void
    {
        $this->avg_rating = $this->reviews()->avg('rating') ?? 0;
        $this->jobs_completed = $this->jobRequests()
                                     ->where('status', 'completed')
                                     ->count();
        $this->save();
    }
}