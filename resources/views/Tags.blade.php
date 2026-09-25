<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/tags.css', 'resources/js/tags.js'])

    <title>Gérer les Tags - Your Shelf</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

</head>

<body>

    <div class="app-container">

        {{-- ── Top Navigation ── --}}
        <div class="top-nav">
            <a href="{{ route('bibliotheque.list') }}" class="nav-link">
                <svg viewBox="0 0 24 24">
                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
                </svg>
                Bibliothèque
            </a>
            <span style="font-size: 15px; color: #8e8e93; font-weight: 500;">
                {{ $tags->count() }} tags
            </span>
        </div>

        {{-- ── Header ── --}}
        <div class="header">
            <h1>Tags</h1>
            <p>Créez, modifiez et organisez vos moods et catégories d'albums.</p>
        </div>

        {{-- ── Section Nouveau Tag ── --}}
        <div class="section-group">
            <div class="section-header">Ajouter un nouveau tag</div>
            <div class="card-panel">
                <form action="{{ route('tags.store') }}" method="POST" class="tag-form">
                    @csrf
                    <div class="form-row">
                        <input type="text" name="name" class="ios-input"
                            placeholder="Nom du tag (ex: Pop, Chill, Années 80...)" required>
                        <input type="hidden" name="color" id="new_tag_color" value="#5B7DB1">
                        <button type="submit" class="btn-ios">Créer</button>
                    </div>

                    <div class="color-picker-group">
                        <span class="color-picker-label">Couleur :</span>
                        @php
                            $colors = ['#5B7DB1', '#F5A623', '#7EC8B0', '#9B59B6', '#E74C3C', '#E87FA0', '#34495E', '#E67E22', '#3498DB', '#8E7CC3', '#1ABC9C', '#2ECC71'];
                        @endphp
                        @foreach ($colors as $index => $color)
                            <div class="color-swatch {{ $index === 0 ? 'active' : '' }}"
                                style="background-color: {{ $color }};"
                                onclick="selectColor('{{ $color }}', this, 'new_tag_color')">
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Section Liste des Tags ── --}}
        <div class="section-group">
            <div class="section-header">Vos tags existants</div>
            <div class="tags-list">
                @forelse ($tags as $tag)
                    <div class="tag-card" id="tag-card-{{ $tag->id }}">
                        <div class="tag-card-header">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span class="tag-badge" style="background-color: {{ $tag->color }};">
                                    {{ $tag->name }}
                                </span>
                                <span class="tag-stats">
                                    {{ $tag->albums_count }} {{ $tag->albums_count > 1 ? 'albums' : 'album' }}
                                </span>
                            </div>

                            <div class="tag-actions">
                                <button type="button" class="action-icon-btn" onclick="toggleEdit({{ $tag->id }})">
                                    Modifier
                                </button>
                                <form action="{{ route('tags.destroy', $tag->id) }}" method="POST"
                                    onsubmit="return confirm('Voulez-vous vraiment supprimer le tag « {{ $tag->name }} » ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-icon-btn danger">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Aperçu des albums associés --}}
                        <div class="tag-albums-preview">
                            @if ($tag->albums->count() > 0)
                                @foreach ($tag->albums as $album)
                                    <a href="{{ route('bibliotheque.show', $album->id) }}"
                                        title="{{ $album->name }} - {{ $album->artist }}">
                                        <img src="{{ $album->cover }}" alt="{{ $album->name }}" class="mini-album-cover">
                                    </a>
                                @endforeach
                            @else
                                <span class="empty-albums-text">Aucun album associé à ce tag pour le moment.</span>
                            @endif
                        </div>

                        {{-- Formulaire d'édition masqué par défaut --}}
                        <div class="edit-form-container" id="edit-container-{{ $tag->id }}">
                            <form action="{{ route('tags.update', $tag->id) }}" method="POST" class="edit-form">
                                @csrf
                                <input type="text" name="name" value="{{ $tag->name }}" class="ios-input"
                                    style="max-width: 220px;" required>
                                <input type="hidden" name="color" id="edit_color_{{ $tag->id }}" value="{{ $tag->color }}">

                                <div class="color-picker-group" style="margin-right: auto;">
                                    @foreach ($colors as $color)
                                        <div class="color-swatch {{ $tag->color == $color ? 'active' : '' }}"
                                            style="background-color: {{ $color }};"
                                            onclick="selectColor('{{ $color }}', this, 'edit_color_{{ $tag->id }}')">
                                        </div>
                                    @endforeach
                                </div>

                                <button type="submit" class="btn-ios"
                                    style="padding: 10px 18px; font-size: 14px;">Enregistrer</button>
                                <button type="button" class="btn-ios btn-ios-secondary"
                                    style="padding: 10px 18px; font-size: 14px;"
                                    onclick="toggleEdit({{ $tag->id }})">Annuler</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="card-panel" style="text-align: center; color: #8e8e93;">
                        Aucun tag disponible. Créez votre premier tag ci-dessus !
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <script>
        function selectColor(color, element, targetInputId) {
            // Mettre à jour le champ caché
            document.getElementById(targetInputId).value = color;

            // Mettre à jour la classe active sur les swatches du même groupe
            const parentGroup = element.parentElement;
            const swatches = parentGroup.querySelectorAll('.color-swatch');
            swatches.forEach(swatch => swatch.classList.remove('active'));
            element.classList.add('active');
        }

        function toggleEdit(tagId) {
            const editContainer = document.getElementById(`edit-container-${tagId}`);
            editContainer.classList.toggle('active');
        }
    </script>
</body>

</html>