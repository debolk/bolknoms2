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
            <a href="{{ action('Administration\UpdateMeal@edit', $meal->id) }}">maaltijd bewerken</a>
        </span>
    </p>

    <h2>
        Bevestigde aanmeldingen
        <i id="print" class="fa fa-fw fa-2x fa-print"></i>
    </h2>

    <p id="print_instructions">
        Bonnetjes opgehaald door: _____________________________
    </p>

    <table id="registrations">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>Naam</th>
                <th>Bolkaccount</th>
                <th>Dieet</th>
                <th><span class="non_print">Verwijderen </span></th>
            </tr>
        </thead>
        <tbody>
            @each('administration/meal/_registration', $meal->registrations()->confirmed()->get(), 'registration')
        </tbody>
    </table>

    @include('administration/meal/_unconfirmed_list')

    <h2>Nieuwe eter toevoegen</h2>
    <form action="#new_registration" id="new_registration" data-meal_id="{{ $meal->id }}">
        <p>
            <label for="user_id">Bolker</label><br>
            <select name="user_id" id="user_id">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
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

    <link rel="stylesheet" href="/stylesheets/print.css" media="print" />
@endsection
