@extends('layouts.master')

@section('title', 'Mijn profiel')

@section('content')
    <h1>Mijn profiel</h1>

    <div class="columns">
        <div class="column">
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
        </div>

        <div class="column">
            <div class="profile">
                <h2>Verzamelplaatjes</h2>
                <p>Door mee-eten verzamel je automatisch Bolknoms verzamelplaatjes. Spaar ze allemaal door zo veel mogelijk mee te eten!</p>
                <p>
                    Je hebt {{ $user->collectibles()->count() }} van de {{ \App\Models\Collectible::count() }} plaatjes gevonden.
                </p>
                @foreach ($user->collectibles as $collectible)
                    <div>
                        <div>
                            <video controls height="400">
                                <source src="{{ $collectible->assetPath() }}" type="video/mp4">
                            </video>
                            <span class="award">{{ \App\Models\Award::for(Auth::user(), $collectible)->awarded }}x!</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="column">
            <div class="profile">
                <h2>Maaltijden waar je bij was</h2>
                <p>
                    @foreach ($user->dateList() as $entry)
                        {{ strftime('%A %e %B %Y', strtotime($entry->meal_timestamp)) }}<br>
                    @endforeach
                </p>
            </div>
        </div>

    </div>
@endsection
