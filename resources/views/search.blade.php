<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @vite(['resources/css/search.css', 'resources/js/search.js'])
    
    <title>Recherche — YourShelf</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
</head>
<body>

    {{-- ── Barre de navigation iOS 11 ── --}}
    <nav class="nav-bar">
        <a href="{{ route('bibliotheque.list') }}" class="nav-back">
            <svg viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
            Bibliothèque
        </a>
        <a href="{{ route('bibliotheque.search') }}" class="nav-reset" title="Nouvelle recherche">+</a>
    </nav>

    {{-- ── En-tête : titre + champ de recherche (relié à l'API Deezer) ── --}}
    <header class="search-header">
        <h1 class="large-title">Recherche</h1>
        <form action="{{ route('bibliotheque.search') }}" method="GET">
            <div class="search-bar">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" name="search" class="search-input" placeholder="Artiste ou album" value="{{ $searchQuery }}" required>
            </div>
        </form>
    </header>

    {{-- ── Résultats Deezer, croisés avec la bibliothèque (bdd) pour savoir ce qui est déjà ajouté ── --}}
    @if (!empty($apiResults))
        <section class="section-group">
            <div class="section-header">Résultats pour « {{ $searchQuery }} »</div>
            <div class="results-grid">
                @foreach ($apiResults as $result)
                    @php
                        $existingAlbum = $albums->first(function ($a) use ($result) {
                            return $a->name === $result['title'] && $a->artist === $result['artist']['name'];
                        });
                    @endphp
                    <div class="result-tile">
                        <img src="{{ $result['cover_medium'] }}" alt="{{ $result['title'] }}" class="result-cover">
                        <div class="result-overlay">
                            <p class="result-title">{{ $result['title'] }}</p>
                            <p class="result-artist">{{ $result['artist']['name'] }}</p>
                        </div>

                        @if ($existingAlbum)
                            {{-- Déjà présent en bdd : on renvoie vers sa fiche --}}
                            <a href="{{ route('bibliotheque.show', $existingAlbum->id) }}" class="result-badge result-badge-added" title="Déjà dans ta bibliothèque">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </a>
                        @else
                            {{-- Pas encore en bdd : formulaire d'ajout vers AlbumController@store --}}
                            <form action="{{ route('bibliotheque.add') }}" method="POST" class="result-badge-form">
                                @csrf
                                <input type="hidden" name="name" value="{{ $result['title'] }}">
                                <input type="hidden" name="artist" value="{{ $result['artist']['name'] }}">
                                <input type="hidden" name="image" value="{{ $result['cover_medium'] }}">
                                <button type="submit" class="result-badge result-badge-add" title="Ajouter à ma bibliothèque">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @elseif($searchQuery)
        <div class="empty-state">
            Aucun résultat pour « {{ $searchQuery }} ».<br>Essaie un autre artiste ou album.
        </div>
    @else
        <div class="empty-state">
            Recherche un artiste ou un album pour l'ajouter à ta bibliothèque.
        </div>
    @endif

</body>
</html>
