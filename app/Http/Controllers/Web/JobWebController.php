<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use App\Models\Provider;
use Illuminate\Http\Request;

class JobWebController extends Controller
{
    public function index()
    {
        $jobs = JobRequest::with(['provider.user', 'trade'])
                          ->where('customer_id', auth()->id())
                          ->latest()
                          ->get();

        return view('jobs.index', compact('jobs'));
    }

    public function create(Provider $provider)
    {
        $provider->load(['user', 'trades']);
        return view('jobs.create', compact('provider'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'provider_id'      => 'required|exists:providers,id',
            'trade_id'         => 'required|exists:trades,id',
            'description'      => 'required|string',
            'location_address' => 'nullable|string',
            'location_area'    => 'nullable|string',
            'scheduled_at'     => 'nullable|date',
        ]);

        $job = JobRequest::create([
            ...$data,
            'customer_id' => auth()->id(),
            'status'      => 'pending',
        ]);

        return redirect("/jobs/{$job->id}")
               ->with('success', 'Job request sent successfully.');
    }

    public function show(JobRequest $job)
    {
        abort_if($job->customer_id !== auth()->id(), 403);
        $job->load(['provider.user', 'trade', 'messages.sender', 'review']);
        return view('jobs.show', compact('job'));
    }

    public function cancel(JobRequest $job)
    {
        abort_if($job->customer_id !== auth()->id(), 403);
        abort_if(! $job->isPending(), 422);
        $job->update(['status' => 'cancelled']);
        return back()->with('success', 'Job cancelled.');
    }

    public function confirm(JobRequest $job)
    {
        abort_if($job->customer_id !== auth()->id(), 403);
        $job->update(['customer_confirmed' => true]);
        return back()->with('success', 'Job confirmed complete. Please leave a review.');
    }

    public function review(Request $request, JobRequest $job)
    {
        abort_if($job->customer_id !== auth()->id(), 403);

        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $job->review()->create([
            'customer_id' => auth()->id(),
            'provider_id' => $job->provider_id,
            'rating'      => $data['rating'],
            'comment'     => $data['comment'] ?? null,
        ]);

        return back()->with('success', 'Review submitted. Thank you!');
    }

    public function sendMessage(Request $request, JobRequest $job)
    {
        abort_if($job->customer_id !== auth()->id(), 403);

        $data = $request->validate([
            'body' => 'required|string',
        ]);

        $job->messages()->create([
            'sender_id' => auth()->id(),
            'body'      => $data['body'],
        ]);

        return back();
    }
}