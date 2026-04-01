@extends('layouts.app')

@section('title', 'Annonces Immobilières - ImmoNow')

@section('content')

<!-- HERO / BARRE DE RECHERCHE -->
<div class="p-5 mb-4 rounded-3 text-white" style="background: linear-gradient(135deg, #2e7d32, #1b5e20);">
    <h1 class="display-5 fw-bold"><i class="fas fa-home me-3"></i>Trouvez votre bien idéal</h1>
    <p class="lead">Parcourez des milliers d'annonces de maisons, appartements et terrains.</p>

    <!-- Formulaire de recherche/filtres -->
    <form method="GET" action="{{ route('listings.index') }}" class="row g-2 mt-3">
        <div class="col-md-3">
            <select name="type" class="form-select">
                <option value="">Vente ou Location</option>
                <option value="vente" {{ request('type') == 'vente' ? 'selected' : '' }}>Vente</option>
                <option value="location" {{ request('type') == 'location' ? 'selected' : '' }}>Location</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="property_type" class="form-select">
                <option value="">Type de bien</option>
                <option value="appartement" {{ request('property_type') == 'appartement' ? 'selected' : '' }}>Appartement</option>
                <option value="maison" {{ request('property_type') == 'maison' ? 'selected' : '' }}>Maison</option>
                <option value="terrain" {{ request('property_type') == 'terrain' ? 'selected' : '' }}>Terrain</option>
                <option value="bureau" {{ request('property_type') == 'bureau' ? 'selected' : '' }}>Bureau</option>
                <option value="commerce" {{ request('property_type') == 'commerce' ? 'selected' : '' }}>Commerce</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" name="city" class="form-control" placeholder="Ville" value="{{ request('city') }}">
        </div>
        <div class="col-md-2">
            <input type="number" name="max_price" class="form-control" placeholder="Prix max (FCFA)" value="{{ request('max_price') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-warning w-100 fw-bold">
                <i class="fas fa-search me-1"></i> Rechercher
            </button>
        </div>
    </form>
</div>

<!-- RESULTATS -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">{{ $listings->total() }} annonce(s) trouvée(s)</h5>
    @if(request()->hasAny(['type','property_type','city','max_price']))
        <a href="{{ route('listings.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-times me-1"></i>Effacer les filtres
        </a>
    @endif
</div>

@if($listings->isEmpty())
    <div class="text-center py-5">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <p class="text-muted fs-5">Aucune annonce ne correspond à votre recherche.</p>
        @auth
            <a href="{{ route('listings.create') }}" class="btn btn-primary">Publier la première annonce</a>
        @endauth
    </div>
@else
    <!-- GRILLE DES ANNONCES -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($listings as $listing)
        <div class="col">
            <div class="card h-100 card-listing shadow-sm">
                <!-- Photo de couverture -->
                @if($listing->coverPhoto)
                    <img src="{{ asset('storage/' . $listing->coverPhoto->path) }}"
                         class="card-img-top" style="height:200px; object-fit:cover;"
                         alt="{{ $listing->title }}">
                @else
                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height:200px;">
                        <i class="fas fa-image fa-3x text-white"></i>
                    </div>
                @endif

                <div class="card-body">
                    <!-- Badges type et bien -->
                    <div class="mb-2">
                        <span class="badge badge-type-{{ $listing->type }} text-white">
                            {{ strtoupper($listing->type) }}
                        </span>
                        <span class="badge bg-secondary ms-1">{{ $listing->property_type }}</span>
                    </div>

                    <h5 class="card-title">{{ Str::limit($listing->title, 50) }}</h5>

                    <p class="text-muted small mb-1">
                        <i class="fas fa-map-marker-alt me-1"></i>{{ $listing->city }}
                    </p>

                    @if($listing->surface)
                        <p class="text-muted small mb-1">
                            <i class="fas fa-ruler-combined me-1"></i>{{ $listing->surface }} m²
                            @if($listing->rooms)
                                &nbsp;&bull;&nbsp;<i class="fas fa-door-open me-1"></i>{{ $listing->rooms }} pièces
                            @endif
                        </p>
                    @endif

                    <p class="fw-bold text-success fs-5 mt-2">
                        {{ number_format($listing->price, 0, ',', ' ') }} FCFA
                        @if($listing->type == 'location')<small class="text-muted">/mois</small>@endif
                    </p>
                </div>

                <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                    <small class="text-muted">Par {{ $listing->user->name }}</small>
                    <a href="{{ route('listings.show', $listing) }}" class="btn btn-primary btn-sm">
                        Voir <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- PAGINATION -->
    <div class="mt-4">
        {{ $listings->withQueryString()->links() }}
    </div>
@endif

@endsection
