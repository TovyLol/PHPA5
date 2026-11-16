@extends('layouts.app')

@section('title', 'Niet gevonden')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center py-5">
            <p class="display-4 fw-bold text-muted mb-2">404</p>
            <h1 class="h3">Pagina niet gevonden</h1>
            <p class="text-muted">Deze pagina of band bestaat niet (meer).</p>
            <a href="{{ route('search') }}" class="btn btn-primary">Bands zoeken</a>
        </div>
    </div>
</div>
@endsection
