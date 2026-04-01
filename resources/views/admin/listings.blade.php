@extends('layouts.app')

@section('title', 'Gestion des Annonces - Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-list me-2"></i>Toutes les Annonces</h3>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Retour
    </a>
</div>

<div class="table-responsive">
    <table class="table table-hover bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
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
            <tr class="{{ $listing->is_flagged ? 'table-danger' : '' }}">
                <td>
                    <a href="{{ route('listings.show', $listing) }}" target="_blank" class="text-decoration-none">
                        {{ Str::limit($listing->title, 35) }}
                    </a>
                </td>
                <td>{{ $listing->user->name }}</td>
                <td><span class="badge badge-type-{{ $listing->type }} text-white">{{ $listing->type }}</span></td>
                <td>{{ $listing->city }}</td>
                <td>{{ number_format($listing->price, 0, ',', ' ') }}</td>
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
                <td class="d-flex gap-1">
                    @if(!$listing->is_flagged)
                    <form method="POST" action="{{ route('admin.flag', $listing) }}">
                        @csrf
                        <button class="btn btn-sm btn-warning" title="Signaler comme frauduleuse">
                            <i class="fas fa-flag"></i>
                        </button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('admin.unflag', $listing) }}">
                        @csrf
                        <button class="btn btn-sm btn-success" title="Réactiver">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('admin.destroy', $listing) }}"
                          onsubmit="return confirm('Supprimer définitivement ?')">
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

@endsection
