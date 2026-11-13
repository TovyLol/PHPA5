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

    <h1 class="h3 mb-4">{{ $band->name }} beheren</h1>

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

                {{-- Biografie --}}
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

            {{-- Rechterkolom: vormgeving met live voorbeeld (TO 4.2) --}}
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

    // Relatieve helderheid volgens WCAG 2.x
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

    // Bandnaam in het voorbeeld meteen bijwerken
    const name = document.getElementById('name');
    name.addEventListener('input', () => {
        document.getElementById('preview-name').textContent = name.value || 'Bandnaam';
    });

    // Voorbeeld tonen van een nieuw gekozen foto
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
