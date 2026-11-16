@extends('layouts.app')

@section('title', $band->name)

{{-- de hele pagina in de kleuren van de band --}}
@push('styles')
<style>
    body { background-color: {{ $band->bg_color }}; color: {{ $band->text_color }}; }
    .epk a:not(.btn) { color: inherit; }
</style>
@endpush

@section('content')
<div class="container epk">
    @can('update', $band)
        {{-- alleen beheerders zien dit --}}
        <div class="alert alert-light d-flex flex-wrap justify-content-between align-items-center gap-2 text-body">
            <span>Dit is de openbare pagina van jouw band.</span>
            <a href="{{ route('bands.edit', $band) }}" class="btn btn-sm btn-primary">Bewerken</a>
        </div>
    @endcan

    <header class="row g-4 align-items-center mb-5">
        @if ($band->photo_path)
            <div class="col-md-5">
                <img src="{{ asset('storage/'.$band->photo_path) }}" class="img-fluid rounded shadow epk-photo" alt="Foto van {{ $band->name }}">
            </div>
        @endif
        <div class="{{ $band->photo_path ? 'col-md-7' : 'col-12 text-center' }}">
            <h1 class="display-4 fw-bold">{{ $band->name }}</h1>
            @if ($band->short_description)
                <p class="lead">{{ $band->short_description }}</p>
            @endif
        </div>
    </header>

    @if ($band->biography)
        <section class="mb-5" aria-labelledby="bio-titel">
            <h2 id="bio-titel" class="h3 mb-3">Biografie</h2>
            {{-- eerst escapen, dan pas <br> toevoegen: zo kan er geen HTML in de bio --}}
            <div class="epk-bio">{!! nl2br(e($band->biography)) !!}</div>
        </section>
    @endif

    @if ($band->videos->isNotEmpty())
        <section class="mb-5" aria-labelledby="video-titel">
            <h2 id="video-titel" class="h3 mb-3">Video's</h2>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                @foreach ($band->videos as $video)
                    <div class="col">
                        <div class="ratio ratio-16x9 rounded overflow-hidden shadow">
                            <iframe src="{{ $video->embedUrl() }}"
                                    title="{{ $video->title ?: 'Video van '.$band->name }}"
                                    loading="lazy"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    allowfullscreen></iframe>
                        </div>
                        @if ($video->title)
                            <p class="mt-2 mb-0 fw-semibold">{{ $video->title }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if (! $band->biography && $band->videos->isEmpty())
        <p class="text-center opacity-75">Deze band heeft nog geen biografie of video's toegevoegd.</p>
    @endif
</div>
@endsection
