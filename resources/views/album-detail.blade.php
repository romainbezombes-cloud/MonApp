<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/album-detail.css', 'resources/js/album-detail.js'])

    <title>{{ $album->name }} — YourShelf</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
</head>
<body>

    {{-- ── Barre de navigation iOS 11 ── --}}
    <nav class="nav-bar">
        <a href="{{ route('bibliotheque.list') }}" class="nav-back">
            <svg viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
            Bibliothèque
        </a>
    </nav>

    {{-- ── Hero : Pochette ── --}}
    <section class="album-hero">
        <div class="album-cover-wrapper">
            <img src="{{ $album->cover }}" alt="{{ $album->name }}" class="album-cover">
        </div>
    </section>

    {{-- ── Infos album ── --}}
    <section class="album-info">
        <h1 class="album-title">{{ $album->name }}</h1>
        <p class="album-artist">{{ $album->artist }}</p>
    </section>

    {{-- ── Tags actifs sur cet album ── --}}
    @if($album->tags->count() > 0)
        <div class="section-group">
            <div class="section-header">Mood actuel</div>
            <div class="section-card">
                <div class="active-tags">
                    @foreach($album->tags as $activeTag)
                        <span class="active-tag-pill" style="background-color: {{ $activeTag->color }};">
                            {{ $activeTag->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ── Sélection des tags ── --}}
    <div class="section-group">
        <div class="section-header">Associer un mood</div>
        <div class="section-card">
            <div class="tags-grid">
                @foreach($tags as $tag)
                    <form action="{{ route('bibliotheque.toggle_tag', ['id' => $album->id, 'tagId' => $tag->id]) }}" method="POST" class="tag-form">
                        @csrf
                        @if($album->tags->contains($tag->id))
                            <button type="submit" class="tag-btn tag-btn-active" style="background-color: {{ $tag->color }};">
                                {{ $tag->name }}
                            </button>
                        @else
                            <button type="submit" class="tag-btn tag-btn-inactive">
                                {{ $tag->name }}
                            </button>
                        @endif
                    </form>
                @endforeach
            </div>
        </div>
    </div>

        <div class="section-group">
            <div class="section-header">Plus de Détails</div>
                <div class="section-card">
                    <p>Sorti en : *année*</p>
                    <p>Genre (d'après Deezer) : *Genre musical*</p>
                    <p>Nombre de morceaux : *nombre*</p>
                </div>
            </div>
        </div>


<!-- Pour supprimer l'album de notre bibliothèque  -->
    <div class="section-group">
                <a href="{{ route('bibliotheque.delete', ['id' => $album->id]) }}">
                    <button class="action-icon-btn danger">Supprimer l'album de la bibliothèque</button>
                </a>
    </div>

    <div class="bottom-spacer"></div>

</body>
</html>
