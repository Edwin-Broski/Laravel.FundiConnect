@extends('layouts.app')
@section('title', $provider->user->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10 sm:px-6 lg:px-8">

    {{-- Profile header --}}
    <div class="bg-white border border-warm-200 rounded-2xl p-6 mb-6">
        <div class="flex items-start gap-4">
            <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-xl flex-shrink-0">
                {{ strtoupper(substr($provider->user->name, 0, 2)) }}
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-xl font-bold text-warm-900">{{ $provider->user->name }}</h1>
                    @if($provider->is_verified)
                    <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full">✓ Verified</span>
                    @endif
                    @if($provider->is_available)
                    <span class="text-xs bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full">● Available</span>
                    @else
                    <span class="text-xs bg-warm-100 text-warm-500 px-2 py-0.5 rounded-full">Unavailable</span>
                    @endif
                </div>

                <p class="text-warm-500 text-sm mt-0.5">
                    {{ $provider->location_area }}@if($provider->location_district), {{ $provider->location_district }}@endif
                </p>

                <div class="flex flex-wrap gap-1 mt-2">
                    @foreach($provider->trades as $trade)
                    <span class="text-xs bg-primary-50 text-primary-700 px-2 py-0.5 rounded-full">
                        {{ $trade->name }}
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- Stats --}}
            <div class="text-right flex-shrink-0">
                <div class="flex items-center gap-1 justify-end">
                    <span class="text-amber-400 text-lg">★</span>
                    <span class="font-bold text-warm-900">{{ number_format($provider->avg_rating, 1) }}</span>
                </div>
                <p class="text-xs text-warm-400">{{ $provider->jobs_completed }} jobs done</p>
            </div>
        </div>

        @if($provider->bio)
        <p class="text-sm text-warm-600 mt-4 leading-relaxed">{{ $provider->bio }}</p>
        @endif

        {{-- Book button --}}
        @if($provider->is_available)
        <div class="mt-5">
            @auth
                <a href="/jobs/create/{{ $provider->id }}"
                   class="inline-block bg-primary-500 hover:bg-primary-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition">
                    Request this fundi
                </a>
            @else
                <a href="/login"
                   class="inline-block bg-primary-500 hover:bg-primary-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition">
                    Login to request
                </a>
            @endauth
        </div>
        @endif
    </div>

    {{-- Reviews --}}
    <div class="bg-white border border-warm-200 rounded-2xl p-6">
        <h2 class="font-semibold text-warm-900 mb-5">
            Reviews
            <span class="text-warm-400 font-normal text-sm ml-1">({{ $provider->reviews->count() }})</span>
        </h2>

        @if($provider->reviews->count())
            <div class="space-y-5">
                @foreach($provider->reviews as $review)
                <div class="border-b border-warm-100 pb-5 last:border-0 last:pb-0">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-sm font-medium text-warm-800">
                            {{ $review->customer->name }}
                        </p>
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $review->rating ? 'text-amber-400' : 'text-warm-200' }} text-sm">★</span>
                            @endfor
                        </div>
                    </div>
                    @if($review->comment)
                    <p class="text-sm text-warm-500">{{ $review->comment }}</p>
                    @endif
                    <p class="text-xs text-warm-300 mt-1">{{ $review->created_at->diffForHumans() }}</p>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-warm-400">No reviews yet. Be the first to book this fundi.</p>
        @endif
    </div>

</div>
@endsection