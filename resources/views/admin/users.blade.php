@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs - Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-users me-2"></i>Utilisateurs Inscrits</h3>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Retour
    </a>
</div>

<div class="table-responsive">
    <table class="table table-hover bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Annonces</th>
                <th>Rôle</th>
                <th>Inscription</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-2"
                             style="width:32px;height:32px;font-size:.9rem;flex-shrink:0;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        {{ $user->name }}
                    </div>
                </td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone ?? '-' }}</td>
                <td><span class="badge bg-primary">{{ $user->listings_count }}</span></td>
                <td>
                    @if($user->is_admin)
                        <span class="badge bg-danger">Admin</span>
                    @else
                        <span class="badge bg-secondary">Utilisateur</span>
                    @endif
                </td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $users->links() }}

@endsection
