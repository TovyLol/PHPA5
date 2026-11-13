@extends('layouts.app')

@section('title', 'Nieuwe band')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nieuwe band</li>
                </ol>
            </nav>

            <div class="card shadow-sm">
                <div class="card-header">Nieuwe band aanmaken</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('bands.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Bandnaam <span class="text-danger">*</span></label>
                            <input id="name" type="text" name="name" maxlength="100" required autofocus
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="short_description" class="form-label">Korte beschrijving</label>
                            <textarea id="short_description" name="short_description" rows="3" maxlength="255"
                                      class="form-control @error('short_description') is-invalid @enderror"
                                      aria-describedby="short_description_help">{{ old('short_description') }}</textarea>
                            <div id="short_description_help" class="form-text">
                                Eén of twee zinnen, deze worden getoond in de zoekresultaten. Maximaal 255 tekens.
                            </div>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <p class="small text-muted">
                            Biografie, foto, video's en kleuren stel je in nadat de band is aangemaakt.
                        </p>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Band aanmaken</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Annuleren</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
