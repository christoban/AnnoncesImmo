<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'property_type',
        'price',
        'location',
        'city',
        'rooms',
        'surface',
        'is_active',
        'is_flagged',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_flagged' => 'boolean',
        'price' => 'decimal:2',
    ];

    // Une annonce appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Une annonce peut avoir plusieurs photos
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    // La photo de couverture
    public function coverPhoto()
    {
        return $this->hasOne(Photo::class)->where('is_cover', true);
    }

    // Une annonce peut être dans plusieurs favoris
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Messages liés à cette annonce
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Scope pour les annonces actives (non bloquées)
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_flagged', false);
    }

}
