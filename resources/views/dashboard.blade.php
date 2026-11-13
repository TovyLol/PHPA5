@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-0">Mijn bands</h1>
            <p class="text-muted mb-0">Welkom, {{ Auth::user()->name }}.</p>
        </div>
        <a href="{{ route('bands.create') }}" class="btn btn-primary">+ Nieuwe band</a>
    </div>

    @if ($bands->isEmpty())
    {{-- geen bands --}}
        <div class="card text-center py-5">
            <div class="card-body">
                <h2 class="h5">Je beheert nog geen bands</h2>
                <p class="text-muted">Maak je eerste band aan en bouw zijn Electronic Press Kit.</p>
                <a href="{{ route('bands.create') }}" class="btn btn-primary">Maak je eerste band aan</a>
            </div>
        </div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
            @foreach ($bands as $band)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        @if ($band->photo_path)
                            <img src="{{ asset('storage/'.$band->photo_path) }}" class="card-img-top band-card-img" alt="Foto van {{ $band->name }}">
                        @else
                            {{--geen foto = eerste letter --}}
                            <div class="card-img-top band-card-img d-flex align-items-center justify-content-center"
                                 style="background-color: {{ $band->bg_color }}; color: {{ $band->text_color }};"
                                 aria-hidden="true">
                                <span class="display-4 fw-bold">{{ mb_strtoupper(mb_substr($band->name, 0, 1)) }}</span>
                            </div>
                        @endif

                        <div class="card-body">
                            <h2 class="h5 card-title">{{ $band->name }}</h2>
                            <p class="card-text text-muted">{{ $band->short_description ?: 'Nog geen beschrijving.' }}</p>
                        </div>

                        <div class="card-footer bg-transparent d-flex justify-content-between align-items-center gap-2">
                            <span class="small text-muted">
                                {{ $band->users_count }} {{ $band->users_count === 1 ? 'beheerder' : 'beheerders' }}
                                · {{ $band->videos_count }} {{ $band->videos_count === 1 ? 'video' : "video's" }}
                            </span>
                            {{-- aanpassen en verwijderen --}}
                            <a href="{{ route('bands.edit', $band) }}" class="btn btn-sm btn-outline-primary stretched-link">Beheren</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
