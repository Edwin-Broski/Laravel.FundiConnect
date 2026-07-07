<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, JobRequest $jobRequest)
    {
        abort_if($jobRequest->customer_id !== $request->user()->id, 403, 'Unauthorized.');
        abort_if(! $jobRequest->isCompleted(), 422, 'Job must be completed before reviewing.');
        abort_if($jobRequest->review()->exists(), 422, 'You have already reviewed this job.');

        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = $jobRequest->review()->create([
            'customer_id' => $request->user()->id,
            'provider_id' => $jobRequest->provider_id,
            'rating'      => $data['rating'],
            'comment'     => $data['comment'] ?? null,
        ]);

        // provider avg_rating updates automatically via Review model booted()

        return response()->json($review, 201);
    }
}