@extends('layouts.app')

@section('title', $band->name.' beheren')

@section('content')
@php
    // check color old input
    $bgColor = old('bg_color', $band->bg_color);
    $textColor = old('text_color', $band->text_color);
@endphp

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $band->name }}</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h1 class="h3 mb-0">{{ $band->name }} beheren</h1>
        <a href="{{ route('epk.show', $band) }}" class="btn btn-outline-secondary" target="_blank" rel="noopener">Bekijk EPK ↗</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            Niet alles is goed ingevuld. Controleer de rood gemarkeerde velden.
        </div>
    @endif

    <form method="POST" action="{{ route('bands.update', $band) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            {{-- Linkerkolom: inhoud --}}
            <div class="col-lg-7">
                {{-- Algemeen --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">Algemeen</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Bandnaam <span class="text-danger">*</span></label>
                            <input id="name" type="text" name="name" maxlength="100" required
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $band->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="short_description" class="form-label">Korte beschrijving</label>
                            <textarea id="short_description" name="short_description" rows="2" maxlength="255"
                                      class="form-control @error('short_description') is-invalid @enderror"
                                      aria-describedby="short_description_help">{{ old('short_description', $band->short_description) }}</textarea>
                            <div id="short_description_help" class="form-text">Wordt getoond in de zoekresultaten. Maximaal 255 tekens.</div>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                
                <div class="card shadow-sm mb-4">
                    <div class="card-header">Biografie</div>
                    <div class="card-body">
                        <label for="biography" class="visually-hidden">Biografie</label>
                        <textarea id="biography" name="biography" rows="10" maxlength="10000"
                                  class="form-control @error('biography') is-invalid @enderror"
                                  aria-describedby="biography_help"
                                  placeholder="Vertel het verhaal van de band: hoe jullie begonnen, je muziekstijl, hoogtepunten...">{{ old('biography', $band->biography) }}</textarea>
                        <div id="biography_help" class="form-text">Gebruik een witregel om een nieuwe alinea te beginnen.</div>
                        @error('biography')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Foto --}}
                <div class="card shadow-sm">
                    <div class="card-header">Foto</div>
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-sm-5">
                                <img id="photo-preview"
                                     src="{{ $band->photo_path ? asset('storage/'.$band->photo_path) : '' }}"
                                     alt="Huidige foto van {{ $band->name }}"
                                     class="img-fluid rounded border band-photo-preview {{ $band->photo_path ? '' : 'd-none' }}">
                                <div id="photo-placeholder"
                                     class="rounded border band-photo-preview d-flex align-items-center justify-content-center text-muted small {{ $band->photo_path ? 'd-none' : '' }}">
                                    Nog geen foto
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <label for="photo" class="form-label">{{ $band->photo_path ? 'Andere foto kiezen' : 'Foto uploaden' }}</label>
                                <input id="photo" type="file" name="photo" accept=".jpg,.jpeg,.png,.webp"
                                       class="form-control @error('photo') is-invalid @enderror"
                                       aria-describedby="photo_help">
                                <div id="photo_help" class="form-text">JPG, PNG of WebP, maximaal 2 MB.</div>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if ($band->photo_path)
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_photo" value="1" id="remove_photo">
                                        <label class="form-check-label" for="remove_photo">Huidige foto verwijderen</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-lg-5">
                <div class="card shadow-sm sticky-lg-top band-sticky">
                    <div class="card-header">Vormgeving EPK-pagina</div>
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="bg_color" class="form-label">Achtergrondkleur</label>
                                <input id="bg_color" type="color" name="bg_color" value="{{ $bgColor }}"
                                       class="form-control form-control-color w-100 @error('bg_color') is-invalid @enderror">
                                @error('bg_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="text_color" class="form-label">Tekstkleur</label>
                                <input id="text_color" type="color" name="text_color" value="{{ $textColor }}"
                                       class="form-control form-control-color w-100 @error('text_color') is-invalid @enderror">
                                @error('text_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <p class="form-label mb-1">Voorbeeld</p>
                        <div id="color-preview" class="rounded border p-4"
                             style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
                            <h2 class="h4" id="preview-name">{{ $band->name }}</h2>
                            <p class="mb-0">Zo ziet de tekst op jullie EPK-pagina eruit.</p>
                        </div>

                        {{-- Contrastwaarschuwing (FO 4.4), wordt door JavaScript getoond of verborgen --}}
                        <div id="contrast-warning" class="alert alert-warning small mt-3 mb-0 d-none" role="status">
                            <strong>Weinig contrast</strong> (<span id="contrast-ratio"></span>:1).
                            De tekst is misschien slecht leesbaar. Kies kleuren die meer van elkaar verschillen.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">Wijzigingen opslaan</button>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Terug naar dashboard</a>
        </div>
    </form>

    {{-- Video's (FO 3.3, TO 4.4). Staat buiten het bandformulier: formulieren mogen niet genest worden --}}
    <div class="card shadow-sm mt-5" id="videos">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Video's</span>
            <span class="badge text-bg-secondary">{{ $band->videos->count() }}</span>
        </div>
        <div class="card-body">
            {{-- Video toevoegen --}}
            <form method="POST" action="{{ route('videos.store', $band) }}" class="row g-2 align-items-start mb-4">
                @csrf
                <div class="col-md-6">
                    <label for="youtube_url" class="form-label">YouTube-link <span class="text-danger">*</span></label>
                    <input id="youtube_url" type="text" name="youtube_url" maxlength="255" required
                           inputmode="url" placeholder="https://www.youtube.com/watch?v=..."
                           class="form-control @error('youtube_url', 'video') is-invalid @enderror"
                           value="{{ old('youtube_url') }}">
                    @error('youtube_url', 'video')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="video_title" class="form-label">Titel <span class="text-muted small">(optioneel)</span></label>
                    <input id="video_title" type="text" name="title" maxlength="100"
                           class="form-control @error('title', 'video') is-invalid @enderror"
                           value="{{ old('title') }}">
                    @error('title', 'video')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2 d-grid">
                    <label class="form-label d-none d-md-block">&nbsp;</label>
                    <button type="submit" class="btn btn-primary">Toevoegen</button>
                </div>
            </form>

            {{-- Huidige video's --}}
            @if ($band->videos->isEmpty())
                <p class="text-muted text-center py-3 mb-0">
                    Nog geen video's. Plak hierboven een YouTube-link om de eerste toe te voegen.
                </p>
            @else
                <ul class="list-group">
                    @foreach ($band->videos as $video)
                        <li class="list-group-item d-flex align-items-center gap-3">
                            <a href="{{ $video->youtube_url }}" target="_blank" rel="noopener" class="flex-shrink-0">
                                <img src="{{ \App\Support\YouTube::thumbnail($video->youtube_id) }}"
                                     alt="" class="rounded video-thumb" loading="lazy">
                            </a>
                            <div class="flex-grow-1 text-truncate">
                                <div class="fw-semibold text-truncate">{{ $video->title ?: 'Video zonder titel' }}</div>
                                <a href="{{ $video->youtube_url }}" target="_blank" rel="noopener" class="small text-muted text-truncate d-block">
                                    {{ $video->youtube_url }}
                                </a>
                            </div>
                            <form method="POST" action="{{ route('videos.destroy', [$band, $video]) }}"
                                  onsubmit="return confirm('Weet je zeker dat je deze video wilt verwijderen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Verwijderen</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- Beheerders (FO 3.3, TO 4.1) --}}
    @can('manageUsers', $band)
        @php($laatsteBeheerder = $band->users->count() <= 1)

        <div class="card shadow-sm mt-4" id="beheerders">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Beheerders</span>
                <span class="badge text-bg-secondary">{{ $band->users->count() }}</span>
            </div>
            <div class="card-body">
                <p class="text-muted small">
                    Beheerders kunnen alles van deze band aanpassen, ook andere beheerders toevoegen en verwijderen.
                </p>

                {{-- Beheerder toevoegen --}}
                <form method="POST" action="{{ route('bands.users.store', $band) }}" class="row g-2 align-items-start mb-4">
                    @csrf
                    <div class="col-md-10">
                        <label for="member_email" class="form-label">E-mailadres van de nieuwe beheerder</label>
                        <input id="member_email" type="email" name="email" maxlength="255" required
                               placeholder="naam@voorbeeld.nl" autocomplete="off"
                               class="form-control @error('email', 'members') is-invalid @enderror"
                               value="{{ old('email') }}" aria-describedby="member_email_help">
                        <div id="member_email_help" class="form-text">De persoon moet al een account hebben.</div>
                        @error('email', 'members')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 d-grid">
                        <label class="form-label d-none d-md-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary">Toevoegen</button>
                    </div>
                </form>

                {{-- Huidige beheerders --}}
                <ul class="list-group">
                    @foreach ($band->users as $beheerder)
                        @php($isIkZelf = $beheerder->is(auth()->user()))
                        <li class="list-group-item d-flex align-items-center gap-3">
                            <div class="flex-grow-1 text-truncate">
                                <div class="fw-semibold">
                                    {{ $beheerder->name }}
                                    @if ($isIkZelf)
                                        <span class="badge text-bg-primary ms-1">jij</span>
                                    @endif
                                </div>
                                <div class="small text-muted text-truncate">{{ $beheerder->email }}</div>
                            </div>

                            @if ($laatsteBeheerder)
                                <span class="small text-muted" title="Een band moet minimaal één beheerder hebben">Laatste beheerder</span>
                            @else
                                {{-- De vraag staat in data-confirm, zo kan een naam met een ' de JavaScript niet breken --}}
                                <form method="POST" action="{{ route('bands.users.destroy', [$band, $beheerder]) }}"
                                      data-confirm="{{ $isIkZelf
                                          ? 'Weet je zeker dat je deze band wilt verlaten? Je kunt hem daarna niet meer beheren.'
                                          : 'Weet je zeker dat je '.$beheerder->name.' als beheerder wilt verwijderen?' }}"
                                      onsubmit="return confirm(this.dataset.confirm);">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        {{ $isIkZelf ? 'Band verlaten' : 'Verwijderen' }}
                                    </button>
                                </form>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endcan
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const bg = document.getElementById('bg_color');
    const text = document.getElementById('text_color');
    const preview = document.getElementById('color-preview');
    const warning = document.getElementById('contrast-warning');
    const ratioEl = document.getElementById('contrast-ratio');

    // relatieve hex kleur
    const luminance = (hex) => {
        const [r, g, b] = [1, 3, 5].map((i) => {
            const c = parseInt(hex.substr(i, 2), 16) / 255;
            return c <= 0.03928 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4);
        });
        return 0.2126 * r + 0.7152 * g + 0.0722 * b;
    };

    const contrast = (a, b) => {
        const [l1, l2] = [luminance(a), luminance(b)].sort((x, y) => y - x);
        return (l1 + 0.05) / (l2 + 0.05);
    };

    const update = () => {
        preview.style.backgroundColor = bg.value;
        preview.style.color = text.value;

        const ratio = contrast(bg.value, text.value);
        ratioEl.textContent = ratio.toFixed(1);
        warning.classList.toggle('d-none', ratio >= 4.5); // 4.5:1 is de WCAG AA-norm
    };

    bg.addEventListener('input', update);
    text.addEventListener('input', update);
    update();

    // live voorbeeld van de naam
    const name = document.getElementById('name');
    name.addEventListener('input', () => {
        document.getElementById('preview-name').textContent = name.value || 'Bandnaam';
    });

    // voorbeeld laten zien
    const photo = document.getElementById('photo');
    photo.addEventListener('change', () => {
        const file = photo.files[0];
        if (!file) return;
        const img = document.getElementById('photo-preview');
        img.src = URL.createObjectURL(file);
        img.classList.remove('d-none');
        document.getElementById('photo-placeholder').classList.add('d-none');
    });
});
</script>
@endpush
