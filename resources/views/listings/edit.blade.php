@extends('layouts.app')

@section('title', 'Modifier l\'annonce - ImmoNow')

@section('content')

<div class="row justify-content-center">
<div class="col-lg-8">

<div class="card shadow">
    <div class="card-header bg-warning">
        <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Modifier l'annonce</h4>
    </div>
    <div class="card-body p-4">

        <form method="POST" action="{{ route('listings.update', $listing) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Titre *</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $listing->title) }}">
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Type d'offre *</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                        <option value="vente" {{ old('type', $listing->type) == 'vente' ? 'selected' : '' }}>Vente</option>
                        <option value="location" {{ old('type', $listing->type) == 'location' ? 'selected' : '' }}>Location</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Type de bien *</label>
                    <select name="property_type" class="form-select @error('property_type') is-invalid @enderror">
                        @foreach(['appartement','maison','terrain','bureau','commerce'] as $pt)
                        <option value="{{ $pt }}" {{ old('property_type', $listing->property_type) == $pt ? 'selected' : '' }}>
                            {{ ucfirst($pt) }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Prix (FCFA) *</label>
                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                       value="{{ old('price', $listing->price) }}">
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Adresse / Quartier *</label>
                    <input type="text" name="location" class="form-control"
                           value="{{ old('location', $listing->location) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Ville *</label>
                    <input type="text" name="city" class="form-control"
                           value="{{ old('city', $listing->city) }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Surface (m²)</label>
                    <input type="number" name="surface" class="form-control"
                           value="{{ old('surface', $listing->surface) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nombre de pièces</label>
                    <input type="number" name="rooms" class="form-control"
                           value="{{ old('rooms', $listing->rooms) }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Description *</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                          rows="5">{{ old('description', $listing->description) }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('listings.show', $listing) }}" class="btn btn-outline-secondary">Annuler</a>
                <button type="submit" class="btn btn-warning px-4">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
            </div>

        </form>
    </div>
</div>

</div>
</div>

@endsection
