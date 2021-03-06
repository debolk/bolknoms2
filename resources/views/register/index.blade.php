@extends('layouts.master')

@section('title', 'Aanmelden voor maaltijden')

@section('content')
    <h1>Aanmelden voor maaltijden</h1>

    @include('register/_user_details')

    <form action="#" id="register_form">

        @if (count($meals) == 0)
            <p class="zero_case">Er zijn geen maaltijden open waarvoor je je kunt aanmelden.</p>
        @else
            <div class="meals">
                @foreach ($meals as $meal)
                    @include('register/_meal', ['meal' => $meal, 'user' => $user])
                @endforeach
            </div>
        @endif
    </form>
@endsection
