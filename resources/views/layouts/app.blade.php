<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'FundiConnect') }} — @yield('title', 'Find Trusted Skilled Workers')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-warm-50 text-warm-800 font-sans antialiased">

    {{-- Navigation --}}
    <nav class="bg-white border-b border-warm-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">F</span>
                    </div>
                    <span class="font-bold text-warm-900 text-lg">FundiConnect</span>
                </a>

                {{-- Nav links --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="/providers" class="text-warm-600 hover:text-warm-900 text-sm font-medium transition">
                        Find a Fundi
                    </a>
                    @auth
                        <a href="/jobs" class="text-warm-600 hover:text-warm-900 text-sm font-medium transition">
                            My Jobs
                        </a>
                    @endauth
                </div>

                {{-- Auth buttons --}}
                <div class="flex items-center gap-3">
                    @guest
                        <a href="/login"
                           class="text-sm font-medium text-warm-600 hover:text-warm-900 transition">
                            Login
                        </a>
                        <a href="/register"
                           class="text-sm font-medium bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition">
                            Sign up
                        </a>
                    @endguest

                    @auth
                        <span class="text-sm text-warm-600">
                            Hi, {{ auth()->user()->name }}
                        </span>
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit"
                                    class="text-sm text-warm-500 hover:text-warm-800 transition">
                                Logout
                            </button>
                        </form>
                    @endauth
                </div>

            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="max-w-6xl mx-auto px-4 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-6xl mx-auto px-4 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Page content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-warm-200 mt-16">
        <div class="max-w-6xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start gap-8">

                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 bg-primary-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xs">F</span>
                        </div>
                        <span class="font-bold text-warm-900">FundiConnect</span>
                    </div>
                    <p class="text-sm text-warm-500 max-w-xs">
                        Connecting Uganda with trusted, skilled workers in your area.
                    </p>
                </div>

                <div class="flex gap-12">
                    <div>
                        <p class="text-sm font-medium text-warm-800 mb-3">Services</p>
                        <ul class="space-y-2 text-sm text-warm-500">
                            <li><a href="/providers?trade=Plumber" class="hover:text-warm-800 transition">Plumbers</a></li>
                            <li><a href="/providers?trade=Electrician" class="hover:text-warm-800 transition">Electricians</a></li>
                            <li><a href="/providers?trade=Carpenter" class="hover:text-warm-800 transition">Carpenters</a></li>
                            <li><a href="/providers?trade=Mechanic" class="hover:text-warm-800 transition">Mechanics</a></li>
                        </ul>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-warm-800 mb-3">Platform</p>
                        <ul class="space-y-2 text-sm text-warm-500">
                            <li><a href="/register" class="hover:text-warm-800 transition">Join as Provider</a></li>
                            <li><a href="/login" class="hover:text-warm-800 transition">Login</a></li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="border-t border-warm-200 mt-8 pt-6 text-sm text-warm-400 text-center">
                © {{ date('Y') }} FundiConnect. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>