@extends('layouts.app')

@section('title', 'Wachtwoord bevestigen')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-1">Wachtwoord bevestigen</h1>
                    <p class="text-muted mb-4">Bevestig je wachtwoord voordat je verdergaat.</p>

                    <form method="POST" action="{{ route('password.confirm') }}" novalidate>
                        @csrf

                        <div class="mb-4">
                            <label for="password" class="form-label">Wachtwoord</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="current-password" autofocus>
                            @error('password')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Bevestigen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
