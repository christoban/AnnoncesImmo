@extends('layouts.app')

@section('title', 'Administration - ImmoNow')

@section('content')

<h3 class="mb-4"><i class="fas fa-shield-alt me-2 text-danger"></i>Tableau de Bord Admin</h3>

<!-- STATS -->
<div class="row g-3 mb-5">
    <div class="col-md-3">
        <div class="card text-center border-0 shadow-sm py-3">
            <div class="fs-1 text-primary fw-bold">{{ $stats['total_listings'] }}</div>
            <div class="text-muted">Total Annonces</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-0 shadow-sm py-3">
            <div class="fs-1 text-success fw-bold">{{ $stats['active_listings'] }}</div>
            <div class="text-muted">Actives</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-0 shadow-sm py-3">
            <div class="fs-1 text-danger fw-bold">{{ $stats['flagged_listings'] }}</div>
            <div class="text-muted">Signalées</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-0 shadow-sm py-3">
            <div class="fs-1 text-warning fw-bold">{{ $stats['total_users'] }}</div>
            <div class="text-muted">Utilisateurs</div>
        </div>
    </div>
</div>

<!-- LIENS RAPIDES -->
<div class="d-flex gap-3 mb-4">
    <a href="{{ route('admin.listings') }}" class="btn btn-outline-dark">
        <i class="fas fa-list me-1"></i>Gérer toutes les annonces
    </a>
    <a href="{{ route('admin.users') }}" class="btn btn-outline-dark">
        <i class="fas fa-users me-1"></i>Gérer les utilisateurs
    </a>
</div>

<!-- ANNONCES SIGNALÉES -->
<h5 class="mb-3 text-danger"><i class="fas fa-flag me-2"></i>Annonces Signalées (frauduleuses)</h5>

@if($flaggedListings->isEmpty())
    <div class="alert alert-success">
        <i class="fas fa-check-circle me-2"></i>Aucune annonce signalée. Tout est propre !
    </div>
@else
    <div class="table-responsive">
        <table class="table table-bordered bg-white shadow-sm">
            <thead class="table-danger">
                <tr>
                    <th>Titre</th>
                    <th>Propriétaire</th>
                    <th>Ville</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($flaggedListings as $listing)
                <tr>
                    <td>
                        <a href="{{ route('listings.show', $listing) }}" target="_blank">
                            {{ Str::limit($listing->title, 40) }}
                        </a>
                    </td>
                    <td>{{ $listing->user->name }}</td>
                    <td>{{ $listing->city }}</td>
                    <td>{{ number_format($listing->price, 0, ',', ' ') }} FCFA</td>
                    <td class="d-flex gap-2">
                        <!-- Réactiver -->
                        <form method="POST" action="{{ route('admin.unflag', $listing) }}">
                            @csrf
                            <button class="btn btn-sm btn-success" title="Réactiver">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <!-- Supprimer définitivement -->
                        <form method="POST" action="{{ route('admin.destroy', $listing) }}"
                              onsubmit="return confirm('Supprimer définitivement ?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
