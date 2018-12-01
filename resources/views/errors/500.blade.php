@extends('layouts.master')

@section('title', 'Foutmelding')

@section('content')
    <div class="notification error">
        <h1>Interne server foutmelding (500)</h1>
        <p>
            Er is een interne fout opgetreden. Probeer het opnieuw. Krijg je weer deze foutmelding? Dan stellen we het zeer op prijs als <a href="mailto:{{ config('mail.admin-mail') }}">je dat aan ons meldt</a>.
        </p>
    </div>
@endsection
