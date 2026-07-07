@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-warm-900">Welcome back</h1>
            <p class="text-warm-500 text-sm mt-1">Login to your FundiConnect account</p>
        </div>

        <div class="bg-white border border-warm-200 rounded-2xl p-8">
            <form method="POST" action="/login" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-warm-700 mb-1">
                        Phone or Email
                    </label>
                    <input
                        type="text"
                        name="login"
                        value="{{ old('login') }}"
                        class="w-full border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                        placeholder="0772123456 or you@email.com"
                    />
                    @error('login')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-warm-700 mb-1">
                        Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        class="w-full border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                        placeholder="••••••••"
                    />
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full bg-primary-500 hover:bg-primary-600 text-white py-2.5 rounded-lg text-sm font-medium transition">
                    Login
                </button>
            </form>

            <p class="text-center text-sm text-warm-500 mt-6">
                Don't have an account?
                <a href="/register" class="text-primary-600 font-medium hover:underline">Sign up</a>
            </p>
        </div>

    </div>
</div>
@endsection