<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Trade;
use Illuminate\Http\Request;

class ProviderWebController extends Controller
{
    public function index(Request $request)
    {
        $trades = Trade::all();

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

        $providers = $query->orderByDesc('avg_rating')->paginate(12);

        return view('providers.index', compact('providers', 'trades'));
    }

    public function show(Provider $provider)
    {
        $provider->load(['user', 'trades', 'reviews.customer']);
        return view('providers.show', compact('provider'));
    }
}