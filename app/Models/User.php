<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nisn',
        'nip',
        'phone',
        'address',
        'avatar',
        'is_online',
        'last_seen',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_online' => 'boolean',
            'last_seen' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return Storage::url($this->avatar);
        }
        
        $defaults = [
            'admin' => 'https://ui-avatars.com/api/?name=Admin&background=2563EB&color=fff',
            'guru' => 'https://ui-avatars.com/api/?name=Guru&background=059669&color=fff',
            'siswa' => 'https://ui-avatars.com/api/?name=Siswa&background=FACC15&color=000',
        ];
        
        return $defaults[$this->role] ?? $defaults['siswa'];
    }

    public function updateLastSeen(): void
    {
        $this->update([
            'last_seen' => now(),
            'is_online' => true,
        ]);
    }

    public function markOffline(): void
    {
        $this->update(['is_online' => false]);
    }
}