<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:guru,siswa'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users', 'required_if:role,guru'],
            'nisn' => ['nullable', 'string', 'max:20', 'unique:users', 'required_if:role,siswa'],
            'nip' => ['nullable', 'string', 'max:20', 'unique:users', 'required_if:role,guru'],
            'phone' => ['nullable', 'string', 'max:15'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $userData = [
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
        ];

        if ($request->role === 'siswa') {
            $userData['nisn'] = $request->nisn;
        } else {
            $userData['email'] = $request->email;
            $userData['nip'] = $request->nip;
        }

        $user = User::create($userData);

        event(new Registered($user));
        Auth::login($user);

        return redirect($this->getRedirectPath($user->role));
    }

    private function getRedirectPath(string $role): string
    {
        return match($role) {
            'admin' => route('admin.dashboard'),
            'guru' => route('guru.dashboard'),
            'siswa' => route('siswa.dashboard'),
            default => '/',
        };
    }
}