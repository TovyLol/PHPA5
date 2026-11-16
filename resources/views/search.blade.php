@extends('layouts.app')

@section('title', $q !== '' ? 'Zoeken: '.$q : 'Bands zoeken')

@section('content')
<div class="container">
    <div class="row justify-content-center mb-4">
        <div class="col-md-8 text-center py-4">
            <h1 class="display-6 fw-bold">Electronic Press Kit</h1>
            <p class="lead text-muted">Zoek een band en bekijk hun EPK.</p>

            <form method="GET" action="{{ route('search') }}" role="search">
                <div class="input-group input-group-lg">
                    <label for="q" class="visually-hidden">Zoek op bandnaam</label>
                    <input type="search" id="q" name="q" value="{{ $q }}" class="form-control"
                           placeholder="Zoek op bandnaam…" maxlength="100">
                    <button type="submit" class="btn btn-primary">Zoeken</button>
                </div>
            </form>
        </div>
    </div>

    @if ($bands->isEmpty())
        {{-- niks gevonden --}}
        <div class="text-center text-muted py-5">
            @if ($q !== '')
                <p class="h5">Geen bands gevonden voor "{{ $q }}".</p>
                <a href="{{ route('search') }}">Toon alle bands</a>
            @else
                <p class="h5">Er zijn nog geen bands.</p>
            @endif
        </div>
    @else
        <p class="text-muted">
            @if ($q !== '')
                {{ $bands->total() }} {{ $bands->total() === 1 ? 'band' : 'bands' }} gevonden voor "{{ $q }}"
            @else
                Alle bands ({{ $bands->total() }})
            @endif
        </p>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
            @foreach ($bands as $band)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        @if ($band->photo_path)
                            <img src="{{ asset('storage/'.$band->photo_path) }}" class="card-img-top band-card-img" alt="Foto van {{ $band->name }}">
                        @else
                            {{-- geen foto = eerste letter in de bandkleuren --}}
                            <div class="card-img-top band-card-img d-flex align-items-center justify-content-center"
                                 style="background-color: {{ $band->bg_color }}; color: {{ $band->text_color }};"
                                 aria-hidden="true">
                                <span class="display-4 fw-bold">{{ mb_strtoupper(mb_substr($band->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div class="card-body">
                            <h2 class="h5 card-title">
                                {{-- stretched-link: de hele kaart is klikbaar --}}
                                <a href="{{ route('epk.show', $band) }}" class="stretched-link text-reset text-decoration-none">{{ $band->name }}</a>
                            </h2>
                            <p class="card-text text-muted">{{ $band->short_description ?: 'Nog geen beschrijving.' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $bands->links() }}
        </div>
    @endif
</div>
@endsection
