<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'role' => ['required', 'in:siswa,guru,admin'],
            'password' => ['required', 'string'],
        ];

        // Validasi berdasarkan role
        if ($this->input('role') === 'siswa') {
            $rules['nisn'] = ['required', 'string'];
        } else {
            $rules['email'] = ['required', 'email'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'role.required' => 'Pilih role terlebih dahulu.',
            'role.in' => 'Role tidak valid.',
            'nisn.required' => 'NISN wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = $this->findUser();

        if (!$user || !Hash::check($this->password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                $this->input('role') === 'siswa' ? 'nisn' : 'email' => 
                    __('Kredensial tidak cocok dengan role yang dipilih.'),
            ]);
        }

        Auth::login($user, $this->boolean('remember'));
        RateLimiter::clear($this->throttleKey());
    }

    protected function findUser(): ?User
    {
        $role = $this->input('role');

        if ($role === 'siswa') {
            return User::where('nisn', $this->nisn)
                       ->where('role', 'siswa')
                       ->first();
        }

        return User::where('email', $this->email)
                   ->where('role', $role)
                   ->first();
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email', '')) . '|' . $this->ip());
    }
}