@extends('layouts.master')

@section('title', 'Vakanties')

@section('content')
    <h1>Vakanties</h1>

    @if ($vacations->count() > 0)
        <table border-spacing=0>
            <thead><tr>
                <th>Start vakantie</th>
                <th>Maaltijden hervat</th>
                <th>&nbsp;</th>
            </tr></thead>
            <tbody>
                @each ('administration/vacations/_vacation', $vacations, 'vacation')
            </tbody>
        </table>
    @else
        <p class="zero_case">
            Er zijn nog geen vakantieperiodes gedefini&euml;erd.
        </p>
    @endif
@endsection
