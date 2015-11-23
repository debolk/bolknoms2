@extends('layouts.master')

@section('title', 'Top eters')

@section('content')
    <h1>Top eters dit verenigingsjaar</h1>
    <ol>
        @foreach ($statistics_ytd as $registration)
            <li>{{ $registration->name }} ({{ $registration->count }})</li>
        @endforeach
    </ol>

    <h1>Top eters all-time</h1>
    <ol>
        @foreach ($statistics_alltime as $registration)
            <li>{{ $registration->name }} ({{ $registration->count }})</li>
        @endforeach
    </ol>
@endsection
