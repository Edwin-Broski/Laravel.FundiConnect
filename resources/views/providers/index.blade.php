@extends('layouts.app')
@section('title', 'Find a Fundi')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10 sm:px-6 lg:px-8">

    {{-- Header + filters --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <h1 class="text-xl font-bold text-warm-900">Find a fundi</h1>

        <form method="GET" action="/providers" class="flex gap-2 flex-wrap">
            <select name="trade"
                class="border border-warm-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                <option value="">All services</option>
                @foreach($trades as $trade)
                    <option value="{{ $trade->name }}"
                        {{ request('trade') === $trade->name ? 'selected' : '' }}>
                        {{ $trade->name }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="area" value="{{ request('area') }}"
                placeholder="Area e.g. Ntinda"
                class="border border-warm-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400" />

            <button type="submit"
                class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                Filter
            </button>

            @if(request('trade') || request('area'))
                <a href="/providers"
                   class="border border-warm-300 text-warm-600 hover:text-warm-900 px-4 py-2 rounded-lg text-sm transition">
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Results --}}
    @if($providers->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($providers as $provider)
            <a href="/providers/{{ $provider->id }}"
               class="bg-white border border-warm-200 rounded-xl p-5 hover:shadow-md hover:border-warm-300 transition">

                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-sm flex-shrink-0">
                        {{ strtoupper(substr($provider->user->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-medium text-warm-900 text-sm">{{ $provider->user->name }}</p>
                        <p class="text-xs text-warm-400">{{ $provider->location_area ?? 'Uganda' }}</p>
                    </div>

                    @if($provider->is_verified)
                    <span class="ml-auto text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full">
                        ✓ Verified
                    </span>
                    @endif
                </div>

                <div class="flex flex-wrap gap-1 mb-3">
                    @foreach($provider->trades as $trade)
                    <span class="text-xs bg-primary-50 text-primary-700 px-2 py-0.5 rounded-full">
                        {{ $trade->name }}
                    </span>
                    @endforeach
                </div>

                @if($provider->bio)
                <p class="text-xs text-warm-500 mb-3 line-clamp-2">{{ $provider->bio }}</p>
                @endif

                <div class="flex items-center justify-between text-xs text-warm-500 pt-3 border-t border-warm-100">
                    <div class="flex items-center gap-1">
                        <span class="text-amber-400">★</span>
                        <span class="font-medium text-warm-700">{{ number_format($provider->avg_rating, 1) }}</span>
                        <span>({{ $provider->reviews->count() }})</span>
                    </div>
                    <span>{{ $provider->jobs_completed }} jobs done</span>
                </div>

            </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $providers->withQueryString()->links() }}
        </div>

    @else
        <div class="text-center py-16 text-warm-400">
            <p class="text-4xl mb-3">🔍</p>
            <p class="font-medium text-warm-600">No fundis found</p>
            <p class="text-sm mt-1">Try a different area or service</p>
        </div>
    @endif

</div>
@endsection