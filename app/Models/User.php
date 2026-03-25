<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'telephone',
        'adresse',
        'ville',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    public function fournisseur(): HasOne
    {
        return $this->hasOne(Fournisseur::class);
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isFournisseur(): bool
    {
        return $this->role === 'fournisseur';
    }
}