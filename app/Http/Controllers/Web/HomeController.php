<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Trade;

class HomeController extends Controller
{
    public function index()
    {
        $trades = Trade::all();

        $featuredProviders = Provider::with(['user', 'trades'])
            ->where('status', 'approved')
            ->where('is_available', true)
            ->orderByDesc('avg_rating')
            ->limit(6)
            ->get();

        return view('home', compact('trades', 'featuredProviders'));
    }
}