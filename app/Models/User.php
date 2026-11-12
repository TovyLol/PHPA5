<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // info
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // dit mag niemand zien
    protected $hidden = [
        'password',
        'remember_token',
    ];
    // ja nee ik laat dit liggen voor de volgende
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // welke bands horen bij de user
    public function bands(): BelongsToMany
    {
        return $this->belongsToMany(Band::class)->withTimestamps();
    }
}
