@extends('layouts.app')
@section('title', 'My Jobs')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10 sm:px-6 lg:px-8">

    <h1 class="text-xl font-bold text-warm-900 mb-6">My Jobs</h1>

    @if($jobs->count())
        <div class="space-y-4">
            @foreach($jobs as $job)
            <a href="/jobs/{{ $job->id }}"
               class="bg-white border border-warm-200 rounded-xl p-5 flex items-center gap-4 hover:shadow-sm hover:border-warm-300 transition">

                <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-sm flex-shrink-0">
                    {{ strtoupper(substr($job->provider->user->name, 0, 2)) }}
                </div>

                <div class="flex-1 min-w-0">
                    <p class="font-medium text-warm-900 text-sm">{{ $job->provider->user->name }}</p>
                    <p class="text-xs text-warm-400 truncate">{{ $job->description }}</p>
                    <p class="text-xs text-warm-300 mt-0.5">{{ $job->trade->name }} · {{ $job->created_at->diffForHumans() }}</p>
                </div>

                {{-- Status badge --}}
                <span class="text-xs px-2.5 py-1 rounded-full font-medium flex-shrink-0
                    @switch($job->status)
                        @case('pending')    bg-amber-50 text-amber-700 @break
                        @case('accepted')   bg-blue-50 text-blue-700 @break
                        @case('in_progress') bg-purple-50 text-purple-700 @break
                        @case('completed')  bg-green-50 text-green-700 @break
                        @case('cancelled')  bg-warm-100 text-warm-500 @break
                        @case('declined')   bg-red-50 text-red-600 @break
                    @endswitch
                ">
                    {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                </span>

            </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-16">
            <p class="text-4xl mb-3">📋</p>
            <p class="font-medium text-warm-600">No jobs yet</p>
            <p class="text-sm text-warm-400 mt-1">Find a fundi and send your first job request</p>
            <a href="/providers"
               class="inline-block mt-4 bg-primary-500 hover:bg-primary-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">
                Find a fundi
            </a>
        </div>
    @endif

</div>
@endsection