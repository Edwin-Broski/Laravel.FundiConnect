@extends('layouts.app')
@section('title', 'Request a Fundi')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10 sm:px-6 lg:px-8">

    <div class="mb-6">
        <a href="/providers/{{ $provider->id }}" class="text-sm text-warm-400 hover:text-warm-700 transition">
            ← Back to profile
        </a>
    </div>

    <div class="bg-white border border-warm-200 rounded-2xl p-6">

        {{-- Provider mini card --}}
        <div class="flex items-center gap-3 pb-5 mb-5 border-b border-warm-100">
            <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-sm">
                {{ strtoupper(substr($provider->user->name, 0, 2)) }}
            </div>
            <div>
                <p class="font-medium text-warm-900 text-sm">{{ $provider->user->name }}</p>
                <p class="text-xs text-warm-400">{{ $provider->location_area }}</p>
            </div>
        </div>

        <h1 class="text-lg font-bold text-warm-900 mb-5">Describe your job</h1>

        <form method="POST" action="/jobs" class="space-y-5">
            @csrf

            <input type="hidden" name="provider_id" value="{{ $provider->id }}" />

            {{-- Trade selection --}}
            <div>
                <label class="block text-sm font-medium text-warm-700 mb-1">Service needed</label>
                <select name="trade_id"
                    class="w-full border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    <option value="">Select a service</option>
                    @foreach($provider->trades as $trade)
                        <option value="{{ $trade->id }}">{{ $trade->name }}</option>
                    @endforeach
                </select>
                @error('trade_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-warm-700 mb-1">Describe the work</label>
                <textarea name="description" rows="4"
                    class="w-full border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                    placeholder="e.g. My kitchen sink pipe is broken and leaking water onto the floor...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Location --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-warm-700 mb-1">Area</label>
                    <input type="text" name="location_area" value="{{ old('location_area') }}"
                        class="w-full border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                        placeholder="e.g. Ntinda" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-warm-700 mb-1">Address</label>
                    <input type="text" name="location_address" value="{{ old('location_address') }}"
                        class="w-full border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                        placeholder="e.g. Plot 23 Ntinda Road" />
                </div>
            </div>

            {{-- Tip --}}
            <div class="bg-amber-50 border border-amber-100 rounded-lg px-4 py-3 text-xs text-amber-800">
                💡 Tip: Never pay the full amount before the work is done. Pay after you are satisfied.
            </div>

            <button type="submit"
                class="w-full bg-primary-500 hover:bg-primary-600 text-white py-3 rounded-lg text-sm font-medium transition">
                Send job request
            </button>
        </form>

    </div>
</div>
@endsection