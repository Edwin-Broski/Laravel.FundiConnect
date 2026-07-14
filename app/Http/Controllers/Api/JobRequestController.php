<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use Illuminate\Http\Request;
use App\Models\JobApplication;

class JobRequestController extends Controller
{
    // customer - post a job
    public function store(Request $request)
    {
        $data = $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'trade_id' => 'required|exists:trades,id',
            'description' => 'required|string',
            'location_address' => 'nullable|string',
            'location_area' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
        ]);

        $job = JobRequest::create([
            ...$data,
            'customer_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        return response()->json($job->load(['provider.user', 'trade']), 201);
    }

    // customer - my jobs
    public function customerJobs(Request $request)
    {
        $jobs = JobRequest::with(['provider.user', 'trade'])
            ->where('customer_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return response()->json($jobs);
    }

    // shared - view single job
    public function show(Request $request, JobRequest $jobRequest)
    {
        $this->authorizeJobAccess($request, $jobRequest);

        return response()->json(
            $jobRequest->load(['customer', 'provider.user', 'trade', 'messages.sender', 'review'])
        );
    }

    // customer - cancel job
    public function cancel(Request $request, JobRequest $jobRequest)
    {
        $this->authorizeCustomer($request, $jobRequest);

        abort_if(! $jobRequest->isPending(), 422, 'Only pending jobs can be cancelled.');

        $jobRequest->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Job cancelled.']);
    }

    // customer - confirm completion
    public function confirmComplete(Request $request, JobRequest $jobRequest)
    {
        $this->authorizeCustomer($request, $jobRequest);

        abort_if($jobRequest->status !== 'completed', 422, 'Job is not marked completed yet.');

        $jobRequest->update([
            'customer_confirmed' => true,
            'status' => 'completed',
        ]);

        return response()->json(['message' => 'Job confirmed complete.']);
    }

    // provider - their jobs
    public function providerJobs(Request $request)
    {
        $provider = $request->user()->provider;

        $jobs = JobRequest::with(['customer', 'trade'])
            ->where('provider_id', $provider->id)
            ->latest()
            ->paginate(15);

        return response()->json($jobs);
    }

    // customer posts open job without selecting a provider
    public function storeOpen(Request $request)
    {
        $data = $request->validate([
            'trade_id' => 'required|exists:trades,id',
            'description' => 'required|string',
            'location_address' => 'nullable|string',
            'location_area' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
        ]);

        $job = JobRequest::create([
            ...$data,
            'customer_id' => $request->user()->id,
            'provider_id' => null,
            'status' => 'open',
        ]);

        return response()->json($job->load('trade'), 201);
    }

    // provider sees all open jobs matching their trades and area
    public function openJobs(Request $request)
    {
        $provider = $request->user()->provider;
        $tradeIds = $provider->trades->pluck('id');

        $jobs = JobRequest::with(['customer', 'trade'])
            ->whereNull('provider_id')
            ->where('status', 'open')
            ->whereIn('trade_id', $tradeIds)
            ->latest()
            ->paginate(15);

        return response()->json($jobs);
    }

    // provider applies to an open job
    public function apply(Request $request, JobRequest $jobRequest)
    {
        abort_if($jobRequest->status !== 'open', 422, 'Job is no longer open.');

        $provider = $request->user()->provider;

        $data = $request->validate([
            'message' => 'nullable|string|max:500',
        ]);

        $existing = $jobRequest->applications()
            ->where('provider_id', $provider->id)
            ->exists();

        abort_if($existing, 422, 'You have already applied to this job.');

        $application = $jobRequest->applications()->create([
            'provider_id' => $provider->id,
            'message' => $data['message'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json($application->load('provider.user'), 201);
    }

    // customer views applications on their job
    public function applications(Request $request, JobRequest $jobRequest)
    {
        abort_if($jobRequest->customer_id !== $request->user()->id, 403, 'Unauthorized.');

        return response()->json(
            $jobRequest->applications()->with('provider.user', 'provider.trades')->get()
        );
    }

    // customer accepts a provider's application
    public function acceptApplication(Request $request, JobRequest $jobRequest, JobApplication $application)
    {
        abort_if($jobRequest->customer_id !== $request->user()->id, 403, 'Unauthorized.');

        // assign provider to job
        $jobRequest->update([
            'provider_id' => $application->provider_id,
            'status' => 'accepted',
        ]);

        // decline all other applications
        $jobRequest->applications()
            ->where('id', '!=', $application->id)
            ->update(['status' => 'declined']);

        // accept this one
        $application->update(['status' => 'accepted']);

        return response()->json(['message' => 'Provider accepted successfully.']);
    }

    // provider - accept
    public function accept(Request $request, JobRequest $jobRequest)
    {
        $this->authorizeProvider($request, $jobRequest);

        abort_if(! $jobRequest->isPending(), 422, 'Job is no longer pending.');

        $jobRequest->update(['status' => 'accepted']);

        return response()->json(['message' => 'Job accepted.']);
    }

    // provider - decline
    public function decline(Request $request, JobRequest $jobRequest)
    {
        $this->authorizeProvider($request, $jobRequest);

        abort_if(! $jobRequest->isPending(), 422, 'Job is no longer pending.');

        $jobRequest->update(['status' => 'declined']);

        return response()->json(['message' => 'Job declined.']);
    }

    // provider - start
    public function start(Request $request, JobRequest $jobRequest)
    {
        $this->authorizeProvider($request, $jobRequest);

        abort_if(! $jobRequest->isAccepted(), 422, 'Job must be accepted first.');

        $jobRequest->update(['status' => 'in_progress']);

        return response()->json(['message' => 'Job started.']);
    }

    // provider - complete with photo
    public function complete(Request $request, JobRequest $jobRequest)
    {
        $this->authorizeProvider($request, $jobRequest);

        abort_if($jobRequest->status !== 'in_progress', 422, 'Job must be in progress.');

        $data = $request->validate([
            'completion_photo' => 'nullable|image|max:4096',
        ]);

        $path = null;
        if ($request->hasFile('completion_photo')) {
            $path = $request->file('completion_photo')->store('completions', 'public');
        }

        $jobRequest->update([
            'status' => 'completed',
            'provider_confirmed' => true,
            'completion_photo' => $path,
        ]);

        return response()->json(['message' => 'Job marked as completed.']);
    }

    // private helpers
    private function authorizeJobAccess(Request $request, JobRequest $job): void
    {
        $user = $request->user();
        $isCustomer = $job->customer_id === $user->id;
        $isProvider = $user->provider && $job->provider_id === $user->provider->id;
        abort_if(! $isCustomer && ! $isProvider && ! $user->isAdmin(), 403, 'Unauthorized.');
    }

    private function authorizeCustomer(Request $request, JobRequest $job): void
    {
        abort_if($job->customer_id !== $request->user()->id, 403, 'Unauthorized.');
    }

    private function authorizeProvider(Request $request, JobRequest $job): void
    {
        $provider = $request->user()->provider;
        abort_if(! $provider || $job->provider_id !== $provider->id, 403, 'Unauthorized.');
    }
}
