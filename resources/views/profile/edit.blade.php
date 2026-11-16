@extends('layouts.app')

@section('title', 'Mijn profiel')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h3 mb-1">Mijn profiel</h1>
            <p class="text-muted mb-4">
                Lid sinds {{ $user->created_at->format('d-m-Y') }}
                · beheerder van {{ $user->bands_count }} {{ $user->bands_count === 1 ? 'band' : 'bands' }}
                (<a href="{{ route('dashboard') }}">bekijken</a>)
            </p>

            {{-- gegevens --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">Gegevens</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Naam</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                   class="form-control @error('name') is-invalid @enderror" required maxlength="255" autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mailadres</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="form-control @error('email') is-invalid @enderror" required maxlength="255" autocomplete="email">
                            <div class="form-text">Hiermee log je in, en hiermee kunnen anderen je toevoegen als beheerder.</div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Gegevens opslaan</button>
                    </form>
                </div>
            </div>

            {{-- wachtwoord, eigen error bag 'wachtwoord' --}}
            <div class="card shadow-sm" id="wachtwoord">
                <div class="card-header">Wachtwoord wijzigen</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password') }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Huidig wachtwoord</label>
                            <input type="password" id="current_password" name="current_password"
                                   class="form-control @error('current_password', 'wachtwoord') is-invalid @enderror"
                                   required autocomplete="current-password">
                            @error('current_password', 'wachtwoord')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Nieuw wachtwoord</label>
                                <input type="password" id="password" name="password"
                                       class="form-control @error('password', 'wachtwoord') is-invalid @enderror"
                                       required minlength="8" autocomplete="new-password">
                                <div class="form-text">Minimaal 8 tekens.</div>
                                @error('password', 'wachtwoord')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Herhaal nieuw wachtwoord</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control" required autocomplete="new-password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Wachtwoord wijzigen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
