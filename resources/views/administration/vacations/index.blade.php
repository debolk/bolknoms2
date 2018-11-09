@extends('layouts.master')

@section('title', 'Vakanties')

@section('content')
    <h1>Vakanties</h1>

    <p>
        @if ($currentVacation)
            Er is nu vakantie. De maaltijden worden hervat op {{ $currentVacation->end->formatLocalized('%e %B %Y') }}.
        @else
            Er is nu geen vakantie.
        @endif
        <br>
        @if ($upcomingVacation)
            De eerstvolgende vakantie is van
            {{ $upcomingVacation->start->formatLocalized('%e %B %Y') }} tot
            {{ $upcomingVacation->end->formatLocalized('%e %B %Y') }}.
        @endif
    </p>

    <h2>Alle vakanties</h2>
    <table border-spacing=0>
        <thead><tr>
            <th>Start vakantie</th>
            <th>Maaltijden hervat</th>
            <th>&nbsp;</th>
        </tr></thead>
        <tbody>
            @include('administration/vacations/_create')

            @if ($vacations->count() > 0)
                @each ('administration/vacations/_vacation', $vacations, 'vacation')
            @else
                <tr>
                    <td colspan=3 class="zero_case">
                        Er zijn nog geen vakantieperiodes gedefini&euml;erd.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection
