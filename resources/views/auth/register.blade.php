@extends('layouts.app')
@section('title', 'Create Account')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-lg">

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-warm-900">Create your account</h1>
            <p class="text-warm-500 text-sm mt-1">Join FundiConnect — it's free</p>
        </div>

        <div class="bg-white border border-warm-200 rounded-2xl p-8">
            <form method="POST" action="/register"
                  enctype="multipart/form-data"
                  class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-warm-700 mb-1">Full name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                        placeholder="Sarah Nakato" />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-warm-700 mb-1">Phone number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                        placeholder="0772123456" />
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-warm-700 mb-1">
                        Email <span class="text-warm-400 font-normal">(optional)</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                        placeholder="you@email.com" />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-warm-700 mb-1">Password</label>
                    <input type="password" name="password"
                        class="w-full border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                        placeholder="At least 6 characters" />
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-warm-700 mb-1">Confirm password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                        placeholder="Repeat password" />
                </div>

                {{-- Role selection --}}
                <div>
                    <label class="block text-sm font-medium text-warm-700 mb-2">I am joining as</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="customer" id="role_customer"
                                class="sr-only" {{ old('role', 'customer') === 'customer' ? 'checked' : '' }} />
                            <div class="role-card border border-warm-300 rounded-lg p-3 text-center transition"
                                 id="card_customer">
                                <p class="text-sm font-medium text-warm-700">👤 Customer</p>
                                <p class="text-xs text-warm-400 mt-0.5">I need a fundi</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="provider" id="role_provider"
                                class="sr-only" {{ old('role') === 'provider' ? 'checked' : '' }} />
                            <div class="role-card border border-warm-300 rounded-lg p-3 text-center transition"
                                 id="card_provider">
                                <p class="text-sm font-medium text-warm-700">🔧 Provider</p>
                                <p class="text-xs text-warm-400 mt-0.5">I offer a service</p>
                            </div>
                        </label>
                    </div>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Provider-only fields --}}
                <div id="provider_fields" class="space-y-5 hidden">

                    <div class="bg-amber-50 border border-amber-100 rounded-lg px-4 py-3 text-xs text-amber-800">
                        📋 Your account will be reviewed by our team before going live. This usually takes less than 24 hours.
                    </div>

                    {{-- Trades --}}
                    <div>
                        <label class="block text-sm font-medium text-warm-700 mb-2">Services you offer</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach(\App\Models\Trade::all() as $trade)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="trade_ids[]" value="{{ $trade->id }}"
                                    class="rounded border-warm-300 text-primary-500 focus:ring-primary-400"
                                    {{ in_array($trade->id, old('trade_ids', [])) ? 'checked' : '' }} />
                                <span class="text-sm text-warm-700">{{ $trade->name }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('trade_ids')
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
                            <label class="block text-sm font-medium text-warm-700 mb-1">District</label>
                            <input type="text" name="location_district" value="{{ old('location_district') }}"
                                class="w-full border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                                placeholder="e.g. Kampala" />
                        </div>
                    </div>

                    {{-- Bio --}}
                    <div>
                        <label class="block text-sm font-medium text-warm-700 mb-1">Bio</label>
                        <textarea name="bio" rows="3"
                            class="w-full border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                            placeholder="Briefly describe your experience and skills...">{{ old('bio') }}</textarea>
                    </div>

                    {{-- ID photo --}}
                    <div>
                        <label class="block text-sm font-medium text-warm-700 mb-1">
                            National ID photo
                            <span class="text-warm-400 font-normal">(front side)</span>
                        </label>
                        <input type="file" name="id_photo" accept="image/*"
                            class="w-full border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400" />
                        @error('id_photo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Certificate --}}
                    <div>
                        <label class="block text-sm font-medium text-warm-700 mb-1">
                            Trade certificate
                            <span class="text-warm-400 font-normal">(optional but recommended)</span>
                        </label>
                        <input type="file" name="certificate_photo" accept="image/*,application/pdf"
                            class="w-full border border-warm-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400" />
                        @error('certificate_photo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <button type="submit"
                    class="w-full bg-primary-500 hover:bg-primary-600 text-white py-2.5 rounded-lg text-sm font-medium transition">
                    Create account
                </button>
            </form>

            <p class="text-center text-sm text-warm-500 mt-6">
                Already have an account?
                <a href="/login" class="text-primary-600 font-medium hover:underline">Login</a>
            </p>
        </div>

    </div>
</div>

<script>
    const customerRadio  = document.getElementById('role_customer');
    const providerRadio  = document.getElementById('role_provider');
    const providerFields = document.getElementById('provider_fields');
    const cardCustomer   = document.getElementById('card_customer');
    const cardProvider   = document.getElementById('card_provider');

    function updateRole() {
        if (providerRadio.checked) {
            providerFields.classList.remove('hidden');
            cardProvider.classList.add('border-primary-500', 'bg-primary-50');
            cardProvider.classList.remove('border-warm-300');
            cardCustomer.classList.remove('border-primary-500', 'bg-primary-50');
            cardCustomer.classList.add('border-warm-300');
        } else {
            providerFields.classList.add('hidden');
            cardCustomer.classList.add('border-primary-500', 'bg-primary-50');
            cardCustomer.classList.remove('border-warm-300');
            cardProvider.classList.remove('border-primary-500', 'bg-primary-50');
            cardProvider.classList.add('border-warm-300');
        }
    }

    customerRadio.addEventListener('change', updateRole);
    providerRadio.addEventListener('change', updateRole);

    // run on page load to handle old() repopulation
    updateRole();
</script>

@endsection