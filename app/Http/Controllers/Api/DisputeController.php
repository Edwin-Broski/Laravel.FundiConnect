<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    public function store(Request $request, JobRequest $jobRequest)
    {
        $user = $request->user();
        $isCustomer = $jobRequest->customer_id === $user->id;
        $isProvider = $user->provider && $jobRequest->provider_id === $user->provider->id;

        abort_if(! $isCustomer && ! $isProvider, 403, 'Unauthorized.');
        abort_if($jobRequest->dispute()->exists(), 422, 'A dispute already exists for this job.');

        $data = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $dispute = $jobRequest->dispute()->create([
            'raised_by' => $user->id,
            'reason'    => $data['reason'],
            'status'    => 'open',
        ]);

        return response()->json($dispute, 201);
    }
}