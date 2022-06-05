@extends('layouts.master')

@section('title', 'Maaltijden')

@section('content')
    <h1>Maaltijden</h1>

    <p>
      <a href="{{ action([\App\Http\Controllers\Administration\CreateMeal::class, 'index']) }}">
      <i class="fa fa-fw fa-plus"></i>
        Nieuwe maaltijd toevoegen
      </a>
    </p>

    <form action="" method="get">
      <p>
        Toon
        <select id=count name=count class="auto-submit">
            <option value=5>5</option>
            <option value=13>13</option>
            <option value=25>25</option>
            <option value=100>100</option>
            <option value=0>alle</option>
        </select>
        maaltijden per lijst
      </p>
    </form>

    <h2>Komende maaltijden</h2>
    @include('administration/meals/_meals', ['meals' => $upcoming_meals])

    <h2>Afgelopen maaltijden</h2>
    @include('administration/meals/_meals', ['meals' => $previous_meals])
@endsection
