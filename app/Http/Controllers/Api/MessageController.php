<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request, JobRequest $jobRequest)
    {
        $this->authorizeAccess($request, $jobRequest);

        // mark messages as read
        $jobRequest->messages()
                   ->where('sender_id', '!=', $request->user()->id)
                   ->update(['is_read' => true]);

        return response()->json(
            $jobRequest->messages()->with('sender')->get()
        );
    }

    public function store(Request $request, JobRequest $jobRequest)
    {
        $this->authorizeAccess($request, $jobRequest);

        $data = $request->validate([
            'body'           => 'nullable|string',
            'attachment_url' => 'nullable|string',
        ]);

        abort_if(empty($data['body']) && empty($data['attachment_url']), 422, 'Message cannot be empty.');

        $message = $jobRequest->messages()->create([
            'sender_id'      => $request->user()->id,
            'body'           => $data['body'] ?? null,
            'attachment_url' => $data['attachment_url'] ?? null,
        ]);

        return response()->json($message->load('sender'), 201);
    }

    private function authorizeAccess(Request $request, JobRequest $job): void
    {
        $user = $request->user();
        $isCustomer = $job->customer_id === $user->id;
        $isProvider = $user->provider && $job->provider_id === $user->provider->id;
        abort_if(! $isCustomer && ! $isProvider, 403, 'Unauthorized.');
    }
}