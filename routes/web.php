<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

/*
 Routes publiques (accessible sans connexion)
*/
Route::get('/', [ListingController::class, 'index'])->name('listings.index');
Route::get('/annonces/{listing}', [ListingController::class, 'show'])
    ->whereNumber('listing')
    ->name('listings.show');

/*
 Routes authentification (générées par Breeze/UI)
*/
require __DIR__.'/auth.php';

/*
 Routes protégées (connexion requise)
*/
Route::middleware('auth')->group(function () {

    // Dashboard utilisateur (route attendue par Breeze)
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Profil utilisateur (routes attendues par Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Annonces : créer, modifier, supprimer
    Route::get('/annonces/creer', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/annonces', [ListingController::class, 'store'])->name('listings.store');
    Route::get('/annonces/{listing}/modifier', [ListingController::class, 'edit'])
        ->whereNumber('listing')
        ->name('listings.edit');
    Route::put('/annonces/{listing}', [ListingController::class, 'update'])
        ->whereNumber('listing')
        ->name('listings.update');
    Route::delete('/annonces/{listing}', [ListingController::class, 'destroy'])
        ->whereNumber('listing')
        ->name('listings.destroy');

    // Mes annonces
    Route::get('/mes-annonces', [ListingController::class, 'myListings'])->name('listings.my');

    // Favoris
    Route::post('/favoris/{listing}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/mes-favoris', [FavoriteController::class, 'index'])->name('favorites.index');

    // Messagerie
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'conversation'])->name('messages.conversation');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
});

/*
 Routes Administration (connexion + rôle admin requis)
*/
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/annonces', [AdminController::class, 'listings'])->name('listings');
    Route::post('/annonces/{listing}/signaler', [AdminController::class, 'flagListing'])
        ->whereNumber('listing')
        ->name('flag');
    Route::post('/annonces/{listing}/reactiver', [AdminController::class, 'unflagListing'])
        ->whereNumber('listing')
        ->name('unflag');
    Route::delete('/annonces/{listing}', [AdminController::class, 'destroyListing'])
        ->whereNumber('listing')
        ->name('destroy');
    Route::get('/utilisateurs', [AdminController::class, 'users'])->name('users');
});
