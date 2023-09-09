@extends('layouts.master')

@section('title', 'Aftekenlijst '.$meal)

@section('content')
    <h1>Aftekenlijst {{ $meal }}</h1>
    <p class="subtitle">
        <i class="fa fa-fw fa-user"></i>
        <span id="count">{{ $meal->registrations()->confirmed()->count() }}</span>
        <i class="fa fa-fw fa-clock-o"></i> {{ $meal->deadline() }}
        <i class="fa fa-fw fa-cutlery"></i> {{ $meal->meal_timestamp->format('H:i') }} uur
        <span class="non_print">
            <a href="{{ action([\App\Http\Controllers\Administration\UpdateMeal::class, 'edit'], $meal->id) }}">maaltijd bewerken</a>
        </span>
    </p>

    <h2>
        Bevestigde aanmeldingen
        <i id="print" class="fa fa-fw fa-2x fa-print"></i>
    </h2>

    <p id="print_instructions">
        Bonnetjes opgehaald door: _____________________________
    </p>

    <table class="registrations-table" id="registrations" style="width: 40em">
        <thead>
            <tr>
                <th class="non_print">&nbsp;</th>
                <th>Naam</th>
                <th class="non_print">Bevestigd</th>
                <th>Dieet</th>
                <th class="non_print">Verwijderen</th>
            </tr>
        </thead>
        <tbody>
            @each('administration/meal/_registration', $registrations_confirmed, 'registration')
        </tbody>
    </table>

    @if (count($registrations_unconfirmed) > 0)
        <div class="non_print">
            <h2>Niet-bevestigde aanmeldingen</h2>
            <div class="notification warning" style="max-width: 40em;">
                <strong>Let op:</strong> we koken niet voor niet-bevestigde aanmeldingen. Je kunt deze namelijk ook niet factureren volgens de <a href="/spelregels">spelregels</a>. Ze staan ook niet op de geprinte lijst voor de kok.
            </div>
            <table class="registrations-table" style="width: 40em">
                <thead>
                    <tr>
                        <th class="non_print">&nbsp;</th>
                        <th>Naam</th>
                        <th class="non_print">Bevestigd</th>
                        <th>Dieet</th>
                        <th class="non_print">Verwijderen</th>
                    </tr>
                </thead>
                <tbody>
                    @each('administration/meal/_registration', $registrations_unconfirmed, 'registration')
                </tbody>
            </table>
        </div>
    @endif

    <h2>Nieuwe eter toevoegen</h2>
    <form action="#new_registration" id="new_registration" data-meal_id="{{ $meal->id }}">
        <p>
            <label for="user_id">Bolker</label><br>
            <select name="user_id" id="user_id">
                @foreach ($users as $dropdown_user)
                    <option value="{{ $dropdown_user->id }}">{{ $dropdown_user->name }}</option>
                @endforeach
            </select>
        </p>
        <p>
            <input type="submit" value="Toevoegen">
        </p>
    </form>
    <p class="non_print">
        <a href="#" id="subscribe_anonymous" data-meal_id="{{ $meal->id }}">Externe aanmelden</a>
    </p>
@endsection
