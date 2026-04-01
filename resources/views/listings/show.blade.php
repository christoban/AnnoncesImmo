@extends('layouts.app')

@section('title', $listing->title . ' - ImmoNow')

@section('content')

<div class="row">
    <!-- COLONNE PRINCIPALE -->
    <div class="col-lg-8">

        <!-- Galerie photos -->
        @if($listing->photos->isNotEmpty()) 
        <div id="photoCarousel" class="carousel slide mb-4 rounded-3 overflow-hidden shadow" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($listing->photos as $i => $photo)
                <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $photo->path) }}"
                         class="d-block w-100" style="height:420px; object-fit:cover;"
                         alt="Photo {{ $i+1 }}">
                </div>
                @endforeach
            </div>
            @if($listing->photos->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#photoCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#photoCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
            @endif
        </div>
        @else
        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mb-4" style="height:300px;">
            <i class="fas fa-image fa-4x text-muted"></i>
        </div>
        @endif

        <!-- Titre et badges -->
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <span class="badge badge-type-{{ $listing->type }} text-white fs-6 me-2">{{ strtoupper($listing->type) }}</span>
                <span class="badge bg-secondary fs-6">{{ $listing->property_type }}</span>
                <h2 class="mt-2">{{ $listing->title }}</h2>
            </div>
            <!-- Bouton favori -->
            @auth
            <form method="POST" action="{{ route('favorites.toggle', $listing) }}">
                @csrf
                <button type="submit" class="btn {{ $isFavorite ? 'btn-danger' : 'btn-outline-danger' }} btn-lg">
                    <i class="fas fa-heart"></i>
                    {{ $isFavorite ? 'Retirer' : 'Favori' }}
                </button>
            </form>
            @endauth
        </div>

        <!-- Prix -->
        <div class="alert alert-success py-2">
            <span class="fs-4 fw-bold">{{ number_format($listing->price, 0, ',', ' ') }} FCFA</span>
            @if($listing->type == 'location') <span class="text-muted">/ mois</span> @endif
        </div>

        <!-- Caractéristiques -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card text-center py-3">
                    <i class="fas fa-map-marker-alt text-primary fs-4"></i>
                    <small class="text-muted">Ville</small>
                    <strong>{{ $listing->city }}</strong>
                </div>
            </div>
            @if($listing->surface)
            <div class="col-6 col-md-3">
                <div class="card text-center py-3">
                    <i class="fas fa-ruler-combined text-primary fs-4"></i>
                    <small class="text-muted">Surface</small>
                    <strong>{{ $listing->surface }} m²</strong>
                </div>
            </div>
            @endif
            @if($listing->rooms)
            <div class="col-6 col-md-3">
                <div class="card text-center py-3">
                    <i class="fas fa-door-open text-primary fs-4"></i>
                    <small class="text-muted">Pièces</small>
                    <strong>{{ $listing->rooms }}</strong>
                </div>
            </div>
            @endif
            <div class="col-6 col-md-3">
                <div class="card text-center py-3">
                    <i class="fas fa-calendar text-primary fs-4"></i>
                    <small class="text-muted">Publié le</small>
                    <strong>{{ $listing->created_at->format('d/m/Y') }}</strong>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-align-left me-2"></i>Description</h5>
                <p class="card-text" style="white-space: pre-wrap;">{{ $listing->description }}</p>
            </div>
        </div>

        <!-- Localisation -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-map-marker-alt me-2"></i>Localisation</h5>
                <p>{{ $listing->location }}, {{ $listing->city }}</p>
            </div>
        </div>

        <!-- Boutons propriétaire -->
        @auth
            @if(Auth::id() === $listing->user_id)
            <div class="d-flex gap-2 mb-4">
                <a href="{{ route('listings.edit', $listing) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>Modifier
                </a>
                <form method="POST" action="{{ route('listings.destroy', $listing) }}"
                      onsubmit="return confirm('Supprimer cette annonce définitivement ?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger"><i class="fas fa-trash me-1"></i>Supprimer</button>
                </form>
            </div>
            @endif
        @endauth

    </div>

    <!-- COLONNE LATÉRALE : Contact vendeur -->
    <div class="col-lg-4">
        <div class="card shadow-sm sticky-top" style="top: 80px;">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user me-2"></i>Vendeur / Propriétaire</h5>
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" style="width:50px;height:50px;font-size:1.3rem;">
                        {{ strtoupper(substr($listing->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <strong>{{ $listing->user->name }}</strong><br>
                        <small class="text-muted">Membre depuis {{ $listing->user->created_at->format('M Y') }}</small>
                    </div>
                </div>

                @auth
                    @if(Auth::id() !== $listing->user_id)
                    <!-- Formulaire de messagerie -->
                    <form method="POST" action="{{ route('messages.store') }}">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $listing->user_id }}">
                        <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Envoyer un message</label>
                            <textarea name="body" class="form-control" rows="4"
                                placeholder="Bonjour, je suis intéressé(e) par votre annonce..."
                                required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane me-2"></i>Envoyer
                        </button>
                    </form>
                    @else
                        <div class="alert alert-info py-2">C'est votre annonce.</div>
                    @endif
                @else
                    <div class="alert alert-warning py-2">
                        <a href="{{ route('login') }}">Connectez-vous</a> pour contacter le vendeur.
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

@endsection
