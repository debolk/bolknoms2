@extends('layouts.master')

@section('title', 'Maaltijden')

@section('content')
    <h1>Maaltijden</h1>

    <p>
      <a href="{{ action('Administration\CreateMeal@index') }}">
      <i class="fa fa-fw fa-plus"></i>
        Nieuwe maaltijd toevoegen
      </a>
    </p>

    <form action="" method="get">
      <p>
        Toon
        <?php echo Form::select('count', array('5' => '5', '13' => '13', '25' => '25', '100' => '100', '0' => 'alle'), Request::get('count', 10), ['class' => 'auto-submit']); ?>
        maaltijden per lijst
      </p>
    </form>

    <h2>Komende maaltijden</h2>
    @include('administration/meals/_meals', ['meals' => $upcoming_meals])

    <h2>Afgelopen maaltijden</h2>
    @include('administration/meals/_meals', ['meals' => $previous_meals])
@endsection
