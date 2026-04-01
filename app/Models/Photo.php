<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'path',
        'is_cover',
    ];

    protected $casts = [
        'is_cover' => 'boolean',
    ];

    // Une photo appartient à une annonce
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    // Retourne l'URL complète de la photo
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
}
