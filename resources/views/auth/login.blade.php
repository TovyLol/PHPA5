@extends('layouts.app')

@section('title', 'Inloggen')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-1">Inloggen</h1>
                    <p class="text-muted mb-4">Log in om je bands te beheren.</p>

                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mailadres</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Wachtwoord</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Ingelogd blijven</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Inloggen</button>
                    </form>

                    @if (Route::has('register'))
                        <p class="text-center text-muted small mt-4 mb-0">
                            Nog geen account? <a href="{{ route('register') }}">Registreer je hier</a>.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
