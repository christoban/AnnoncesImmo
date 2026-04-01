<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    // Affiche la liste de toutes les annonces actives (page d'accueil/recherche)
    public function index(Request $request)
    {
        $query = Listing::active()->with(['user', 'coverPhoto']);

        // Filtre par type (vente ou location)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtre par type de bien
        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        // Filtre par ville
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Filtre par prix maximum
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filtre par prix minimum
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        $listings = $query->latest()->paginate(12);

        return view('listings.index', compact('listings'));
    }

    // Affiche le détail d'une annonce
    public function show(Listing $listing)
    {
        // Vérifier que l'annonce est accessible
        if (!$listing->is_active || $listing->is_flagged) {
            abort(404);
        }

        $listing->load(['user', 'photos']);

        // Vérifie si l'utilisateur connecté a mis en favori
        $isFavorite = false;
        if (Auth::check()) {
            $isFavorite = $listing->favorites()->where('user_id', Auth::id())->exists();
        }

        return view('listings.show', compact('listing', 'isFavorite'));
    }

    // Formulaire de création d'annonce
    public function create()
    {
        return view('listings.create');
    }

    // Enregistre la nouvelle annonce
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string|min:20',
            'type'          => 'required|in:vente,location',
            'property_type' => 'required|in:appartement,maison,terrain,bureau,commerce',
            'price'         => 'required|numeric|min:1',
            'location'      => 'required|string|max:255',
            'city'          => 'required|string|max:100',
            'rooms'         => 'nullable|integer|min:1',
            'surface'       => 'nullable|numeric|min:1',
            'photos'        => 'nullable|array|max:5',
            'photos.*'      => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Créer l'annonce
        $listing = Listing::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        // Gérer les photos uploadées
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('listings', 'public');
                Photo::create([
                    'listing_id' => $listing->id,
                    'path'       => $path,
                    'is_cover'   => ($index === 0), // La 1ère photo est la couverture
                ]);
            }
        }

        return redirect()->route('listings.show', $listing)
            ->with('success', 'Votre annonce a été publiée avec succès !');
    }

    // Formulaire de modification
    public function edit(Listing $listing)
    {
        // Seul le propriétaire peut modifier
        $this->authorize('update', $listing);
        return view('listings.edit', compact('listing'));
    }

    // Enregistre les modifications
    public function update(Request $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string|min:20',
            'type'          => 'required|in:vente,location',
            'property_type' => 'required|in:appartement,maison,terrain,bureau,commerce',
            'price'         => 'required|numeric|min:1',
            'location'      => 'required|string|max:255',
            'city'          => 'required|string|max:100',
            'rooms'         => 'nullable|integer|min:1',
            'surface'       => 'nullable|numeric|min:1',
        ]);

        $listing->update($validated);

        return redirect()->route('listings.show', $listing)
            ->with('success', 'Annonce mise à jour avec succès !');
    }

    // Supprime une annonce
    public function destroy(Listing $listing)
    {
        $this->authorize('delete', $listing);

        // Supprimer les photos du stockage
        foreach ($listing->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }

        $listing->delete();

        return redirect()->route('listings.index')
            ->with('success', 'Annonce supprimée.');
    }

    // Mes annonces (espace personnel)
    public function myListings()
    {
        $listings = Auth::user()->listings()->with('coverPhoto')->latest()->paginate(10);
        return view('listings.my_listings', compact('listings'));
    }
}
