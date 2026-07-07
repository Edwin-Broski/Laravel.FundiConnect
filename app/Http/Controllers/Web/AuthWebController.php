<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthWebController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['login'])
                    ->orWhere('phone', $data['login'])
                    ->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['login' => 'Incorrect email or password.']);
        }

        if (! $user->is_active) {
            return back()->withErrors(['login' => 'Your account has been suspended.']);
        }

        Auth::login($user);

        return redirect('/jobs');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{
    $data = $request->validate([
        'name'               => 'required|string|max:255',
        'phone'              => 'nullable|string|unique:users',
        'email'              => 'nullable|email|unique:users',
        'password'           => 'required|string|min:6|confirmed',
        'role'               => 'required|in:customer,provider',
        'bio'                => 'nullable|string',
        'location_area'      => 'nullable|string',
        'location_district'  => 'nullable|string',
        'trade_ids'          => 'nullable|array',
        'trade_ids.*'        => 'exists:trades,id',
        'id_photo'           => 'nullable|image|max:4096',
        'certificate_photo'  => 'nullable|mimes:jpg,jpeg,png,pdf|max:4096',
    ]);

    $user = User::create([
        'name'     => $data['name'],
        'phone'    => $data['phone'] ?? null,
        'email'    => $data['email'] ?? null,
        'password' => $data['password'],
        'role'     => $data['role'],
    ]);

    if ($user->role === 'provider') {
        $idPhotoPath = null;
        $certPhotoPath = null;

        if ($request->hasFile('id_photo')) {
            $idPhotoPath = $request->file('id_photo')
                                   ->store('provider-docs/id', 'public');
        }

        if ($request->hasFile('certificate_photo')) {
            $certPhotoPath = $request->file('certificate_photo')
                                     ->store('provider-docs/certificates', 'public');
        }

        $provider = Provider::create([
            'user_id'           => $user->id,
            'bio'               => $data['bio'] ?? null,
            'location_area'     => $data['location_area'] ?? null,
            'location_district' => $data['location_district'] ?? null,
            'id_photo'          => $idPhotoPath,
            'certificate_photo' => $certPhotoPath,
            'status'            => 'pending',
        ]);

        if (!empty($data['trade_ids'])) {
            $provider->trades()->sync($data['trade_ids']);
        }

        Auth::login($user);

        return redirect('/jobs')
               ->with('success', 'Account created. Our team will review and approve your profile within 24 hours.');
    }

    Auth::login($user);

    return redirect('/jobs');
}

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}