@extends('layouts.app')

@section('title', 'Mes Annonces - ImmoNow')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-list me-2 text-success"></i>Mes Annonces</h3>
    <a href="{{ route('listings.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i>Nouvelle annonce
    </a>
</div>

@if($listings->isEmpty())
    <div class="text-center py-5">
        <i class="fas fa-home fa-3x text-muted mb-3"></i>
        <p class="text-muted">Vous n'avez pas encore publié d'annonce.</p>
        <a href="{{ route('listings.create') }}" class="btn btn-primary">Publier une annonce</a>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-hover bg-white shadow-sm rounded">
            <thead class="table-dark">
                <tr>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Ville</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($listings as $listing)
                <tr>
                    <td>
                        <a href="{{ route('listings.show', $listing) }}" class="text-decoration-none fw-bold">
                            {{ Str::limit($listing->title, 40) }}
                        </a>
                    </td>
                    <td>
                        <span class="badge badge-type-{{ $listing->type }} text-white">{{ $listing->type }}</span>
                    </td>
                    <td>{{ $listing->city }}</td>
                    <td>{{ number_format($listing->price, 0, ',', ' ') }} FCFA</td>
                    <td>
                        @if($listing->is_flagged)
                            <span class="badge bg-danger">Signalée</span>
                        @elseif($listing->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $listing->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('listings.edit', $listing) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('listings.destroy', $listing) }}" class="d-inline"
                              onsubmit="return confirm('Supprimer cette annonce ?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $listings->links() }}
@endif

@endsection
