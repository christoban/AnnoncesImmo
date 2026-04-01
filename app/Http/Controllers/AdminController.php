<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Tableau de bord admin
    public function dashboard()
    {
        $stats = [
            'total_listings'   => Listing::count(),
            'active_listings'  => Listing::where('is_active', true)->count(),
            'flagged_listings' => Listing::where('is_flagged', true)->count(),
            'total_users'      => User::count(),
            'total_messages'   => Message::count(),
        ];

        $flaggedListings = Listing::where('is_flagged', true)
            ->with('user')
            ->latest()
            ->get();

        return view('admin.dashboard', compact('stats', 'flaggedListings'));
    }

    // Liste toutes les annonces (admin)
    public function listings()
    {
        $listings = Listing::with('user')->latest()->paginate(20);
        return view('admin.listings', compact('listings'));
    }

    // Signaler une annonce comme frauduleuse
    public function flagListing(Listing $listing)
    {
        $listing->update(['is_flagged' => true, 'is_active' => false]);
        return back()->with('success', 'Annonce signalée et désactivée.');
    }

    // Réactiver une annonce
    public function unflagListing(Listing $listing)
    {
        $listing->update(['is_flagged' => false, 'is_active' => true]);
        return back()->with('success', 'Annonce réactivée.');
    }

    // Supprimer une annonce (admin)
    public function destroyListing(Listing $listing)
    {
        $listing->delete();
        return back()->with('success', 'Annonce supprimée définitivement.');
    }

    // Liste des utilisateurs
    public function users()
    {
        $users = User::withCount('listings')->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }
}
