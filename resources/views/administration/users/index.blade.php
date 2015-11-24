@extends('layouts.master')

@section('title', 'Gebruikers')

@section('content')
    <h1>Gebruikers</h1>

    @if ($users->count() > 0)
        <table border-spacing=0>
            <thead><tr>
                <th>Naam</th>
                <th>Dieet</th>
                <th>Dieet aanpassen</th>
                <th>Geblokkeerd</th>
            </tr></thead>
            <tbody>
                @each ('administration/users/_user', $users, 'user')
            </tbody>
        </table>
    @else
        <p class="zero_case">
            Er zijn geen gebruikers
        </p>
    @endif
@endsection
