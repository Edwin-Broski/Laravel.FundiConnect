<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trade;

class TradeController extends Controller
{
    public function index()
    {
        return response()->json(Trade::all());
    }
}