@extends('layouts.master')

@section('title', 'Vakanties')

@section('content')
    <h1>Vakanties</h1>

    <p>
        Bolknoms maakt elke maandag tot donderdag automatisch een nieuwe maaltijd
        aan om 18:30 uur. Vakantieperiodes kun je hier instellen. In een vakantieperiode
        zal Bolknoms geen nieuwe maaltijden aanmaken.
    </p>

    <p>
        @if ($currentVacation)
            Het is nu vakantie. De maaltijden worden hervat op {{ $currentVacation->end->isoFormat('D MMMM YYYY') }}.
        @else
            Er is nu geen vakantie.
        @endif

        @if ($upcomingVacation)
            De eerstvolgende vakantie begint op
            {{ $upcomingVacation->start->isoFormat('D MMMM YYYY') }}.
            De maaltijden beginnen dan weer op
            {{ $upcomingVacation->end->isoFormat('D MMMM YYYY') }}.
        @endif
    </p>

    <h2>Alle vakanties</h2>
    <table border-spacing=0>
        <thead>
            <tr>
                <th>Start vakantie</th>
                <th>Maaltijden hervat</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
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
