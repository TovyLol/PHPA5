@extends('layouts.app')

@section('title', 'Geen toegang')

@section('content')
{{--    ja nee --}}
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center py-5">
            <p class="display-4 fw-bold text-muted mb-2">403</p>
            <h1 class="h3">Geen toegang</h1>
            <p class="text-muted">
                Je hebt geen toegang tot deze pagina. Je kunt alleen bands beheren waaraan je gekoppeld bent.
            </p>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Naar mijn dashboard</a>
            @else
                <a href="{{ url('/') }}" class="btn btn-primary">Naar de startpagina</a>
            @endauth
        </div>
    </div>
</div>
@endsection
