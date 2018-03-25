@extends('layouts.master')

@section('title', 'Top eters')

@section('content')
    <div class="top-list">
        <h1>Top eters dit verenigingsjaar</h1>
        <ol>
            @foreach ($statistics_ytd as $registration)
                <li {{ $user->name === $registration->name ? 'class=attention' : '' }}>
                    {{ $registration->name }} ({{ $registration->count }})
                </li>
            @endforeach
        </ol>
    </div>

    <div class="top-list">
        <h1>Top eters all-time</h1>
        <ol>
            @foreach ($statistics_alltime as $registration)
                <li {{ $user->name === $registration->name ? 'class=attention' : '' }}>
                    {{ $registration->name }} ({{ $registration->count }})
                </li>
            @endforeach
        </ol>
    </div>
@endsection
