@extends('layouts.app')

@section('title', 'Mes Favoris - ImmoNow')

@section('content')

<h3 class="mb-4"><i class="fas fa-heart text-danger me-2"></i>Mes Annonces Favorites</h3>

@if($favorites->isEmpty())
    <div class="text-center py-5">
        <i class="fas fa-heart-broken fa-3x text-muted mb-3"></i>
        <p class="text-muted">Vous n'avez aucun favori pour l'instant.</p>
        <a href="{{ route('listings.index') }}" class="btn btn-primary">Parcourir les annonces</a>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($favorites as $favorite)
            @if($favorite->listing)
            <div class="col">
                <div class="card h-100 card-listing shadow-sm">
                    @if($favorite->listing->coverPhoto)
                        <img src="{{ asset('storage/' . $favorite->listing->coverPhoto->path) }}"
                             class="card-img-top" style="height:180px; object-fit:cover;">
                    @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="height:180px;">
                            <i class="fas fa-image fa-2x text-white"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <span class="badge badge-type-{{ $favorite->listing->type }} text-white mb-2">
                            {{ strtoupper($favorite->listing->type) }}
                        </span>
                        <h5 class="card-title">{{ Str::limit($favorite->listing->title, 45) }}</h5>
                        <p class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i>{{ $favorite->listing->city }}</p>
                        <p class="fw-bold text-success">{{ number_format($favorite->listing->price, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between">
                        <a href="{{ route('listings.show', $favorite->listing) }}" class="btn btn-primary btn-sm">Voir</a>
                        <form method="POST" action="{{ route('favorites.toggle', $favorite->listing) }}">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-heart-broken me-1"></i>Retirer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
    <div class="mt-4">{{ $favorites->links() }}</div>
@endif

@endsection
