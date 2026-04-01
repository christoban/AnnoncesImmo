<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'phone',
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
            'is_admin' => 'boolean',
        ];
    }

    // Un utilisateur peut avoir plusieurs annonces
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    // Un utilisateur peut avoir plusieurs favoris
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Messages envoyés
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
                
    // Messages reçus
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
