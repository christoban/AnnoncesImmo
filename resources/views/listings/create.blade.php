@extends('layouts.app')

@section('title', 'Publier une annonce - ImmoNow')

@section('content')

<div class="row justify-content-center">
<div class="col-lg-8">

<div class="card shadow">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Publier une nouvelle annonce</h4>
    </div>
    <div class="card-body p-4">

        <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Titre -->
            <div class="mb-3">
                <label class="form-label fw-bold">Titre de l'annonce *</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}" placeholder="Ex: Beau F3 au centre-ville de Yaoundé">
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Type vente/location + type bien -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Type d'offre *</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                        <option value="">-- Choisir --</option>
                        <option value="vente" {{ old('type') == 'vente' ? 'selected' : '' }}>Vente</option>
                        <option value="location" {{ old('type') == 'location' ? 'selected' : '' }}>Location</option>
                    </select>
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Type de bien *</label>
                    <select name="property_type" class="form-select @error('property_type') is-invalid @enderror">
                        <option value="">-- Choisir --</option>
                        <option value="appartement" {{ old('property_type') == 'appartement' ? 'selected' : '' }}>Appartement</option>
                        <option value="maison" {{ old('property_type') == 'maison' ? 'selected' : '' }}>Maison</option>
                        <option value="terrain" {{ old('property_type') == 'terrain' ? 'selected' : '' }}>Terrain</option>
                        <option value="bureau" {{ old('property_type') == 'bureau' ? 'selected' : '' }}>Bureau</option>
                        <option value="commerce" {{ old('property_type') == 'commerce' ? 'selected' : '' }}>Commerce</option>
                    </select>
                    @error('property_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Prix -->
            <div class="mb-3">
                <label class="form-label fw-bold">Prix (FCFA) *</label>
                <div class="input-group">
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                           value="{{ old('price') }}" placeholder="Ex: 25000000">
                    <span class="input-group-text">FCFA</span>
                </div>
                @error('price') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <!-- Localisation -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Adresse / Quartier *</label>
                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                           value="{{ old('location') }}" placeholder="Ex: Quartier Bastos">
                    @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Ville *</label>
                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                           value="{{ old('city') }}" placeholder="Ex: Yaoundé">
                    @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Surface et pièces -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Surface (m²)</label>
                    <input type="number" name="surface" class="form-control @error('surface') is-invalid @enderror"
                           value="{{ old('surface') }}" placeholder="Ex: 80">
                    @error('surface') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nombre de pièces</label>
                    <input type="number" name="rooms" class="form-control @error('rooms') is-invalid @enderror"
                           value="{{ old('rooms') }}" placeholder="Ex: 3">
                    @error('rooms') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label class="form-label fw-bold">Description *</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                          rows="5" placeholder="Décrivez votre bien en détail...">{{ old('description') }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Photos -->
            <div class="mb-4">
                <label class="form-label fw-bold">Photos (max 5, max 2Mo chacune)</label>
                <input type="file" name="photos[]" class="form-control @error('photos') is-invalid @enderror"
                       multiple accept="image/jpeg,image/png,image/jpg">
                <small class="text-muted">La première photo sera la photo principale de l'annonce.</small>
                @error('photos') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                @error('photos.*') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('listings.index') }}" class="btn btn-outline-secondary">Annuler</a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-paper-plane me-2"></i>Publier l'annonce
                </button>
            </div>
        </form>

    </div>
</div>

</div>
</div>

@endsection
