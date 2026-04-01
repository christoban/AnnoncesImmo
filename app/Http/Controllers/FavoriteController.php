<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // Ajouter ou retirer des favoris (toggle)
    public function toggle(Listing $listing)
    {
        $existing = Favorite::where('user_id', Auth::id())
            ->where('listing_id', $listing->id)
            ->first();

        if ($existing) {
            // Si déjà en favori → on le retire
            $existing->delete();
            $message = 'Annonce retirée de vos favoris.';
        } else {
            // Sinon → on l'ajoute
            Favorite::create([
                'user_id'    => Auth::id(),
                'listing_id' => $listing->id,
            ]);
            $message = 'Annonce ajoutée à vos favoris !';
        }

        return back()->with('success', $message);
    }

    // Affiche tous les favoris de l'utilisateur connecté
    public function index()
    {
        $favorites = Auth::user()->favorites()
            ->with(['listing.coverPhoto', 'listing.user'])
            ->latest()
            ->paginate(10);

        return view('listings.favorites', compact('favorites'));
    }
}
