<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    // public - browse providers
    public function index(Request $request)
    {
        $query = Provider::with(['user', 'trades'])
                         ->where('status', 'approved')
                         ->where('is_available', true);

        if ($request->trade) {
            $query->whereHas('trades', fn($q) =>
                $q->where('name', $request->trade)
            );
        }

        if ($request->area) {
            $query->where('location_area', 'ilike', '%' . $request->area . '%');
        }

        if ($request->sort === 'rating') {
            $query->orderByDesc('avg_rating');
        } elseif ($request->sort === 'jobs') {
            $query->orderByDesc('jobs_completed');
        }

        return response()->json($query->paginate(15));
    }

    // public - view single provider
    public function show(Provider $provider)
    {
        return response()->json(
            $provider->load(['user', 'trades', 'reviews.customer'])
        );
    }

    // public - provider reviews
    public function reviews(Provider $provider)
    {
        return response()->json(
            $provider->reviews()->with('customer')->latest()->paginate(10)
        );
    }

    // provider - view own profile
    public function myProfile(Request $request)
    {
        return response()->json(
            $request->user()->load('provider.trades')
        );
    }

    // provider - update own profile
    public function updateProfile(Request $request)
    {
        $provider = $request->user()->provider;

        $data = $request->validate([
            'bio'               => 'nullable|string',
            'location_area'     => 'nullable|string',
            'location_district' => 'nullable|string',
            'trade_ids'         => 'nullable|array',
            'trade_ids.*'       => 'exists:trades,id',
        ]);

        $provider->update($data);

        if (isset($data['trade_ids'])) {
            $provider->trades()->sync($data['trade_ids']);
        }

        return response()->json($provider->load('trades'));
    }

    // provider - toggle availability
    public function toggleAvailability(Request $request)
    {
        $provider = $request->user()->provider;
        $provider->update(['is_available' => ! $provider->is_available]);

        return response()->json([
            'is_available' => $provider->is_available,
        ]);
    }

    // provider - stats
    public function stats(Request $request)
    {
        $provider = $request->user()->provider;

        return response()->json([
            'jobs_completed' => $provider->jobs_completed,
            'avg_rating'     => round($provider->avg_rating, 2),
            'total_reviews'  => $provider->reviews()->count(),
            'pending_jobs'   => $provider->jobRequests()
                                         ->where('status', 'pending')
                                         ->count(),
        ]);
    }
}