<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/bibliotheque.css', 'resources/js/app.js'])

    <title>Ma bibliothèque - YourShelf</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">  
    
</head>

<body>

    <div class="app-container">

        {{-- ── Top Navigation ── --}}
        <div class="top-nav">
            <a href="{{ route('tags.index') }}" class="nav-link">
                Tags
            </a>
            <a href="{{ route('bibliotheque.search') }}" class="nav-link"
                style="font-size: 28px; font-weight: 300;">
                +
            </a>
        </div>

        {{-- ── Header & Search ── --}}
        <div class="header">
            <img src="{{ asset('assets/Vector.svg') }}" alt="Logo" style="width: 300px;">
        </div>

        <h2 class="section-title">Ma bibliothèque</h2>

        {{-- ── Cover Flow 3D (Swipe manuel) ── --}}
        @php
            $favoriteAlbums = $albums->take(100);
        @endphp

        <div class="coverflow-container" id="coverflow">
            @foreach ($favoriteAlbums as $index => $album)
                <a href="{{ route('bibliotheque.show', $album->id) }}" class="coverflow-item" data-index="{{ $index }}">
                    <img src="{{ $album->cover }}" alt="{{ $album->name }}" class="coverflow-cover">
                </a>
            @endforeach
        </div>

        {{-- ── Lignes d'Albums par Tags (Marquee auto) ── --}}
        <div class="tags-section">
            @foreach ($tags as $tag)
                @php
                    $tagAlbums = $tag->albums;
                @endphp
                @if($tagAlbums->count() > 0)
                    <div class="tag-row">
                        <div class="tag-title" style="color: {{ $tag->color }}">
                            {{ $tag->name }}
                        </div>

                        <div class="marquee-container">
                            {{-- On duplique la liste des albums 2 fois pour créer la boucle parfaite infinie --}}
                            {{-- La durée dépend du nombre d'albums pour garder une vitesse constante --}}
                            @php
                                $speed = max(20, $tagAlbums->count() * 5); // 5s par album environ, min 20s
                            @endphp
                            <div class="marquee-content" style="animation: marquee-scroll {{ $speed }}s linear infinite;">
                                @foreach ($tagAlbums as $album)
                                    <a href="{{ route('bibliotheque.show', $album->id) }}" class="marquee-item">
                                        <img src="{{ $album->cover }}" alt="{{ $album->name }}" class="marquee-cover">
                                    </a>
                                @endforeach
                                {{-- DUPLICATION POUR LA BOUCLE INFINIE --}}
                                @foreach ($tagAlbums as $album)
                                    <a href="{{ route('bibliotheque.show', $album->id) }}" class="marquee-item">
                                        <img src="{{ $album->cover }}" alt="{{ $album->name }}" class="marquee-cover">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

    </div>
    



    {{-- ── Script pour le Swipe du Cover Flow ── --}}
    <script>
        const container = document.getElementById('coverflow');
        const items = document.querySelectorAll('.coverflow-item');
        let currentIndex = 0;
        const totalItems = items.length;

        function updateCoverFlow() {
            if (totalItems === 0) return;

            items.forEach((item, i) => {
                let offset = i - currentIndex;

                // Gérer la boucle mathématique
                if (offset < -Math.floor(totalItems / 2)) offset += totalItems;
                if (offset > Math.floor(totalItems / 2)) offset -= totalItems;

                // Position Centrale (Actif)
                if (offset === 0) {
                    item.style.transform = 'translateX(0) scale(1.1) rotateY(0deg)';
                    item.style.zIndex = 10;
                    item.style.opacity = 1;
                    item.style.pointerEvents = 'auto'; // Cliquable seulement au centre
                }
                // Éléments à Gauche
                else if (offset < 0) {
                    item.style.transform = `translateX(${offset * 75 - 60}px) scale(0.85) rotateY(45deg)`;
                    item.style.zIndex = 5 + offset;
                    item.style.opacity = 1 + (offset * 0.3);
                    item.style.pointerEvents = 'none'; // Empêche de cliquer sur le lien si sur le côté
                }
                // Éléments à Droite
                else if (offset > 0) {
                    item.style.transform = `translateX(${offset * 75 + 60}px) scale(0.85) rotateY(-45deg)`;
                    item.style.zIndex = 5 - offset;
                    item.style.opacity = 1 - (offset * 0.3);
                    item.style.pointerEvents = 'none';
                }
            });
        }

        // --- GESTION DU SWIPE & DRAG ---
        let startX = 0;
        let isDragging = false;

        function handleDragStart(x) {
            startX = x;
            isDragging = true;
        }

        function handleDragEnd(x) {
            if (!isDragging) return;
            isDragging = false;

            const diff = x - startX;
            // Seuil de 40px pour déclencher un swipe
            if (diff > 40) {
                // Swipe Droite -> Album précédent
                currentIndex = (currentIndex - 1 + totalItems) % totalItems;
                updateCoverFlow();
            } else if (diff < -40) {
                // Swipe Gauche -> Album suivant
                currentIndex = (currentIndex + 1) % totalItems;
                updateCoverFlow();
            }
        }

        // Événements Tactiles (Smartphones/Tablettes)
        container.addEventListener('touchstart', (e) => handleDragStart(e.touches[0].clientX), { passive: true });
        container.addEventListener('touchend', (e) => handleDragEnd(e.changedTouches[0].clientX));

        // Événements Souris (Desktop)
        container.addEventListener('mousedown', (e) => handleDragStart(e.clientX));
        container.addEventListener('mouseup', (e) => handleDragEnd(e.clientX));
        container.addEventListener('mouseleave', (e) => {
            if (isDragging) handleDragEnd(e.clientX);
        });

        // Initialisation
        updateCoverFlow();
    </script>
    
</body>

</html>