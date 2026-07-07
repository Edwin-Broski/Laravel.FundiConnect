@extends('layouts.app')
@section('title', 'Job Details')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10 sm:px-6 lg:px-8">

    <div class="mb-5">
        <a href="/jobs" class="text-sm text-warm-400 hover:text-warm-700 transition">← My jobs</a>
    </div>

    {{-- Job card --}}
    <div class="bg-white border border-warm-200 rounded-2xl p-6 mb-5">

        <div class="flex items-start justify-between gap-4 mb-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-sm">
                    {{ strtoupper(substr($job->provider->user->name, 0, 2)) }}
                </div>
                <div>
                    <p class="font-semibold text-warm-900">{{ $job->provider->user->name }}</p>
                    <p class="text-xs text-warm-400">{{ $job->trade->name }}</p>
                </div>
            </div>

            <span class="text-xs px-3 py-1 rounded-full font-medium
                @switch($job->status)
                    @case('pending')     bg-amber-50 text-amber-700 @break
                    @case('accepted')    bg-blue-50 text-blue-700 @break
                    @case('in_progress') bg-purple-50 text-purple-700 @break
                    @case('completed')   bg-green-50 text-green-700 @break
                    @case('cancelled')   bg-warm-100 text-warm-500 @break
                    @case('declined')    bg-red-50 text-red-600 @break
                @endswitch
            ">
                {{ ucfirst(str_replace('_', ' ', $job->status)) }}
            </span>
        </div>

        <div class="space-y-2 text-sm text-warm-600">
            <p><span class="font-medium text-warm-800">Description:</span> {{ $job->description }}</p>
            @if($job->location_area)
            <p><span class="font-medium text-warm-800">Area:</span> {{ $job->location_area }}</p>
            @endif
            @if($job->location_address)
            <p><span class="font-medium text-warm-800">Address:</span> {{ $job->location_address }}</p>
            @endif
            <p><span class="font-medium text-warm-800">Requested:</span> {{ $job->created_at->format('d M Y, g:i A') }}</p>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap gap-2 mt-5 pt-5 border-t border-warm-100">
            @if($job->isPending())
                <form method="POST" action="/jobs/{{ $job->id }}/cancel">
                    @csrf @method('PATCH')
                    <button class="text-sm border border-warm-300 text-warm-600 hover:text-warm-900 px-4 py-2 rounded-lg transition">
                        Cancel request
                    </button>
                </form>
            @endif

            @if($job->status === 'completed' && !$job->customer_confirmed)
                <form method="POST" action="/jobs/{{ $job->id }}/confirm">
                    @csrf @method('PATCH')
                    <button class="text-sm bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
                        Confirm job done
                    </button>
                </form>
            @endif
        </div>

    </div>

    {{-- Messages / Inbox --}}
    <div class="bg-white border border-warm-200 rounded-2xl p-6 mb-5">
        <h2 class="font-semibold text-warm-900 mb-4">Messages</h2>

        <div class="space-y-3 mb-5 max-h-80 overflow-y-auto">
            @forelse($job->messages as $message)
                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md px-4 py-2.5 rounded-2xl text-sm
                        {{ $message->sender_id === auth()->id()
                            ? 'bg-primary-500 text-white rounded-br-sm'
                            : 'bg-warm-100 text-warm-800 rounded-bl-sm' }}">
                        <p>{{ $message->body }}</p>
                        <p class="text-xs mt-1 {{ $message->sender_id === auth()->id() ? 'text-primary-200' : 'text-warm-400' }}">
                            {{ $message->created_at->format('g:i A') }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-warm-400 text-center py-4">
                    No messages yet. Send a message to the provider.
                </p>
            @endforelse
        </div>

        {{-- Message input --}}
        @if(!in_array($job->status, ['completed', 'cancelled', 'declined']))
        <form method="POST" action="/jobs/{{ $job->id }}/messages" class="flex gap-2">
            @csrf
            <input type="text" name="body"
                placeholder="Type a message or share your location..."
                class="flex-1 border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400" />
            <button type="submit"
                class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition">
                Send
            </button>
        </form>
        @endif
    </div>

    {{-- Review section --}}
    @if($job->customer_confirmed && !$job->review)
    <div class="bg-white border border-warm-200 rounded-2xl p-6">
        <h2 class="font-semibold text-warm-900 mb-4">Leave a review</h2>

        <form method="POST" action="/jobs/{{ $job->id }}/review" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-warm-700 mb-2">Rating</label>
                <div class="flex gap-2">
                    @for($i = 1; $i <= 5; $i++)
                    <label class="cursor-pointer">
                        <input type="radio" name="rating" value="{{ $i }}" class="sr-only peer" />
                        <span class="text-2xl text-warm-200 peer-checked:text-amber-400 hover:text-amber-300 transition">★</span>
                    </label>
                    @endfor
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-warm-700 mb-1">Comment (optional)</label>
                <textarea name="comment" rows="3"
                    class="w-full border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                    placeholder="How was the service?"></textarea>
            </div>

            <button type="submit"
                class="bg-primary-500 hover:bg-primary-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">
                Submit review
            </button>
        </form>
    </div>
    @endif

    @if($job->review)
    <div class="bg-green-50 border border-green-200 rounded-2xl p-5 text-sm text-green-800">
        ✓ You rated this job {{ $job->review->rating }}/5.
        @if($job->review->comment) "{{ $job->review->comment }}" @endif
    </div>
    @endif

</div>
@endsection