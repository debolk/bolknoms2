@extends('layouts.master')

@section('title', 'Mijn profiel')

@section('content')
    <h1>Mijn profiel</h1>

    <div class="profile">
        <h2>Dieetwensen</h2>

        @if ($user->handicap)
            <h3 id="handicap" data-handicap="{{ $user->handicap }}">&ldquo;{{ $user->handicap }}&rdquo;</h3>
        @else
            <h3 id="handicap" data-handicap="" class="no_diet">Geen dieet ingesteld</h3>
        @endif

        <button id="set_profile_handicap">
            Dieetwensen instellen
        </button>
    </div>

    <div class="profile">
        <h2>Foto instellen</h2>

        <img src="/photo" alt="Je huidige profielfoto" title="Je huidige profielfoto">

        <p>
            Je kunt je profielfoto veranderen in Gosa via <a href="http://gosa.i.bolkhuis.nl">gosa.i.bolkhuis.nl</a>. Hiervoor moet je wel eerst verbinding maken met <a href="http://wiki.debolk.nl/index.php?title=ICT#Internet_op_de_soci.C3.ABteit">Bolknet</a> of de <a href="http://wiki.debolk.nl/index.php?title=ICT#Verbinding_met_de_VPN_maken">VPN</a>.
        </p>
    </div>

    <div class="profile">
        <h2>Ranking dit collegejaar</h2>

        <div class="medal {{ $medal }}">
            <i class="fa fa-fw fa-5x fa-trophy"></i>
        </div>

        <?php if ($rank !== null): ?>
            <h3>
                {{ $rank }}e plaats <br>
                {{ $count }}x meegegeten
            </h3>
        <?php else: ?>
            <h3>
                0x meegegeten
            </h3>
        <?php endif; ?>
    </div>

    <div class="profile">
        <h2>Maaltijden waar je hebt gegeten</h2>
        <ul>
            <?php foreach ($user->registrations()->join('meals', 'registrations.meal_id', '=', 'meals.id')->orderBy('meal_timestamp', 'desc')->get() as $registration): ?>
                <li>{{ $registration->longDate() }}</li>
            <?php endforeach; ?>
        </ul>
    </div>
@endsection
