<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ImmoNow - Plateforme Immobilière')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2e7d32;
            --secondary: #ff8f00;
        }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; }
        .navbar-brand { font-weight: 700; color: var(--primary) !important; font-size: 1.5rem; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: #1b5e20; border-color: #1b5e20; }
        .badge-type-vente { background: #e53935; }
        .badge-type-location { background: #1e88e5; }
        .card-listing:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); transition: all .3s; }
        .card-listing { transition: all .3s; }
        footer { background: #1a1a2e; color: #ccc; padding: 30px 0; margin-top: 60px; }
    </style>
    @stack('styles')
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('listings.index') }}">
            <i class="fas fa-home me-2"></i>ImmoNow
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('listings.index') }}">Annonces</a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('listings.create') }}">
                        <i class="fas fa-plus-circle text-success"></i> Publier
                    </a>
                </li>
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto">
                @auth
                    <!-- Messagerie avec badge non lus -->
                    @php $unread = \App\Http\Controllers\MessageController::unreadCount(); @endphp
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('messages.index') }}">
                            <i class="fas fa-envelope"></i>
                            @if($unread > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unread }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('favorites.index') }}">
                            <i class="fas fa-heart text-danger"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('listings.my') }}">Mes annonces</a></li>
                            <li><a class="dropdown-item" href="{{ route('favorites.index') }}">Mes favoris</a></li>
                            @if(Auth::user()->is_admin)
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="{{ route('admin.dashboard') }}">Administration</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Se déconnecter</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm ms-2" href="{{ route('register') }}">S'inscrire</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- MESSAGES FLASH -->
<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<!-- CONTENU PRINCIPAL -->
<main class="container my-4">
    @isset($header)
        <div class="mb-4 d-flex justify-content-between align-items-center">
            {{ $header }}
        </div>
    @endisset

    @isset($slot)
        {{ $slot }}
    @else
        @yield('content')
    @endisset
</main>

<!-- FOOTER -->
<footer>
    <div class="container text-center">
        <p class="mb-0">&copy; {{ date('Y') }} ImmoNow — Plateforme d'annonces immobilières</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
