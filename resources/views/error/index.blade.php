@extends('layouts.master')

@section('title', 'Administratie')

@section('content')
    <h1>Foutmelding</h1>
    <p>
        Er is een fatale fout opgetreden. De pagina kon niet worden gevonden, of de actie die je probeerde is niet uitgevoerd. De foutmelding wordt, indien bekend, hieronder toegelicht:
    </p>
    <p class="error_message">
        {{ $code }}
    </p>
    <p>
        Als je ingelogd bent, helpt het soms om <a href="/logout">uit te loggen</a> en proberen je aan te melden zonder Bolkaccount. Heb je nog vragen? Je kunt altijd contact opnemen: <a href="mailto:{{ env('MAIL_ADMIN_MAIL') }}">mail de admin</a>.
    </p>
@endsection
