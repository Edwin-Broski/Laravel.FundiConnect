<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\JobRequest;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function pendingProviders()
    {
        return response()->json(
            Provider::with('user')->where('status', 'pending')->latest()->get()
        );
    }

    public function approveProvider(Provider $provider)
    {
        $provider->update(['status' => 'approved', 'is_verified' => true]);
        return response()->json(['message' => 'Provider approved.']);
    }

    public function rejectProvider(Provider $provider)
    {
        $provider->update(['status' => 'rejected']);
        return response()->json(['message' => 'Provider rejected.']);
    }

    public function suspendProvider(Provider $provider)
    {
        $provider->update(['status' => 'suspended']);
        $provider->user->update(['is_active' => false]);
        return response()->json(['message' => 'Provider suspended.']);
    }

    public function disputes()
    {
        return response()->json(
            Dispute::with(['jobRequest', 'raisedBy'])
                   ->where('status', '!=', 'resolved')
                   ->latest()
                   ->get()
        );
    }

    public function resolveDispute(Request $request, Dispute $dispute)
    {
        $data = $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $dispute->update([
            'status'      => 'resolved',
            'admin_notes' => $data['admin_notes'],
        ]);

        return response()->json(['message' => 'Dispute resolved.']);
    }

    public function stats()
    {
        return response()->json([
            'total_users'      => User::count(),
            'total_providers'  => Provider::where('status', 'approved')->count(),
            'pending_providers'=> Provider::where('status', 'pending')->count(),
            'total_jobs'       => JobRequest::count(),
            'completed_jobs'   => JobRequest::where('status', 'completed')->count(),
            'open_disputes'    => Dispute::where('status', 'open')->count(),
        ]);
    }
}