@extends('layouts.app')
@section('title', 'Find Trusted Skilled Workers in Uganda')

@section('content')

{{-- Hero --}}
<section class="bg-white border-b border-warm-200">
    <div class="max-w-6xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <h1 class="text-4xl font-bold text-warm-900 leading-tight mb-4">
                Find a trusted fundi <br>
                <span class="text-primary-500">near you, today</span>
            </h1>
            <p class="text-warm-500 text-lg mb-8">
                Connect with verified plumbers, electricians, carpenters and mechanics in Uganda. Real reviews, real people.
            </p>

            {{-- Search bar --}}
            <form action="/providers" method="GET" class="flex gap-2">
                <input
                    type="text"
                    name="area"
                    placeholder="Your area e.g. Ntinda, Mukono..."
                    class="flex-1 border border-warm-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                />
                <button
                    type="submit"
                    class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition">
                    Search
                </button>
            </form>
        </div>
    </div>
</section>

{{-- Trade categories --}}
<section class="max-w-6xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
    <h2 class="text-lg font-semibold text-warm-800 mb-6">Browse by service</h2>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach($trades as $trade)
        <a href="/providers?trade={{ $trade->name }}"
           class="bg-white border border-warm-200 rounded-xl p-5 hover:border-primary-400 hover:shadow-sm transition group text-center">
            <div class="text-2xl mb-2">
                @switch($trade->name)
                    @case('Plumber') 🔧 @break
                    @case('Electrician') ⚡ @break
                    @case('Carpenter') 🪚 @break
                    @case('Mechanic') 🔩 @break
                    @case('Painter') 🎨 @break
                    @case('Mason') 🧱 @break
                    @case('Welder') 🔥 @break
                    @case('Cleaner') 🧹 @break
                    @default 🛠️
                @endswitch
            </div>
            <p class="text-sm font-medium text-warm-700 group-hover:text-primary-600 transition">
                {{ $trade->name }}
            </p>
        </a>
        @endforeach
    </div>
</section>

{{-- Featured providers --}}
@if($featuredProviders->count())
<section class="max-w-6xl mx-auto px-4 pb-16 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-warm-800">Top rated fundis</h2>
        <a href="/providers" class="text-sm text-primary-500 hover:text-primary-700 font-medium transition">
            View all →
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($featuredProviders as $provider)
        <a href="/providers/{{ $provider->id }}"
           class="bg-white border border-warm-200 rounded-xl p-5 hover:shadow-md hover:border-warm-300 transition">

            {{-- Avatar + name --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-sm flex-shrink-0">
                    {{ strtoupper(substr($provider->user->name, 0, 2)) }}
                </div>
                <div>
                    <p class="font-medium text-warm-900 text-sm">{{ $provider->user->name }}</p>
                    <p class="text-xs text-warm-400">{{ $provider->location_area ?? 'Uganda' }}</p>
                </div>
            </div>

            {{-- Trades --}}
            <div class="flex flex-wrap gap-1 mb-3">
                @foreach($provider->trades as $trade)
                <span class="text-xs bg-primary-50 text-primary-700 px-2 py-0.5 rounded-full">
                    {{ $trade->name }}
                </span>
                @endforeach
            </div>

            {{-- Rating + jobs --}}
            <div class="flex items-center justify-between text-xs text-warm-500">
                <div class="flex items-center gap-1">
                    <span class="text-amber-400">★</span>
                    <span class="font-medium text-warm-700">{{ number_format($provider->avg_rating, 1) }}</span>
                </div>
                <span>{{ $provider->jobs_completed }} jobs done</span>
            </div>

        </a>
        @endforeach
    </div>
</section>
@endif

@endsection