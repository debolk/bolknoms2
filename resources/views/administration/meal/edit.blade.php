@extends('layouts.master')

@section('title', 'Maaltijd bewerken')

@section('content')
    <h1>Maaltijd bewerken</h1>

    @include('form/_error')

    <form action="{{ action('Administration\UpdateMeal@update', $meal->id) }}" method="post" accept-charset="utf-8">
        <p>
            <label class="label" for="meal_timestamp">Datum en tijd</label><br>
            <input type="text" value="{{ date('d-m-Y G:i', strtotime($meal->meal_timestamp)) }}" name="meal_timestamp">
        </p>

        <p>
            <label class="label" for="locked_timestamp">Inschrijving sluit op</label><br>
            <input type="text" value="{{ date('d-m-Y G:i', strtotime($meal->locked_timestamp)) }}" name="locked_timestamp">
        </p>
        <p>
            <label class="label" for="event">Omschrijving</label><br>
            <input type="text" value="{{ $meal->event }}" name="event">
        </p>
        <p>
            <input type="submit" value="Wijzigingen opslaan" />
            of <a href="{{ action('Administration\ShowMeal@show', $meal->id) }}">niet opslaan</a>
        </p>
    </form>
@endsection
