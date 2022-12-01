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
        <h2>API token</h2>
        <p>
            Met jouw API token kun je de Bolknoms API gebruiken. Dit token mag alles uitvoeren wat jij ook mag in Bolknoms.
        </p>
        <p>
            <button id="token-reset">Reset token</button>
        </p>
        <p id="token-output">
            <strong>Token:</strong><br>
            <span id="token-secret"></span>
        </p>
        <p>
            Je krijgt je token maar één keer te zien, maar je kunt de token altijd hier resetten.
        </p>
    </div>

    <div class="profile">
        <h2>Maaltijden waar je hebt gegeten</h2>
        <ul>
            @foreach ($user->dateList() as $entry)
                <li>{{ strftime('%A %e %B %Y', strtotime($entry->meal_timestamp)) }}</li>
            @endforeach
        </ul>
    </div>
@endsection
