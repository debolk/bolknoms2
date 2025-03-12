@extends('layouts.master')

@section('title', 'Nieuwe maaltijd toevoegen')

@section('content')
    <h1>Nieuwe maaltijd toevoegen</h1>

    @include('form/_error')

    <form action="{{ action([\App\Http\Controllers\Administration\CreateMeal::class, 'create']) }}" method="post"
        accept-charset="utf-8">
        <p>
            <label class="label" for="meal_timestamp">Datum en tijd</label><br>
            <input type="text" placeholder="{{ date('d-m-Y 18:30') }}" autofocus name="meal_timestamp"
                value="{{ old('meal_timestamp', null) }}">
        </p>
        <p>
            <label class="label" for="locked_timestamp">Inschrijving sluit op</label><br>
            <input type="text" placeholder="{{ date('d-m-Y 15:00') }}" name="locked_timestamp"
                value="{{ old('locked_timestamp', null) }}">
        </p>
        <p>
            <label class="label" for="event">Omschrijving</label><br>
            <input type="text" placeholder="" name="event" value="{{ old('event', null) }}">
        </p>
        <p>
            <label class="label" for="capacity">Capaciteit</label><br>
            <input type="text" placeholder="" name="capacity" value="{{ old('capacity', null) }}">
            <br><small>Laat leeg om geen limiet te hanteren.</small>
        </p>
        <p>
            <input type="submit" value="Maaltijd toevoegen" />
            of <a href="{{ action([\App\Http\Controllers\Administration\Meals::class, 'index']) }}">niet toevoegen</a>
        </p>
    </form>
@endsection
