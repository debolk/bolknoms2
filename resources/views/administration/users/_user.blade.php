<tr>
    <td>
        <img class="user-picture" src="{{ action('OAuth@photoFor', $user->username) }}">
    </td>
    <td>{{ $user->name }}</td>
    <td class="handicap">{{ $user->handicap or 'Geen dieet ingesteld' }}</td>
    <td>
        <button class="edit-handicap" data-id="{{ $user->id }}" data-handicap="{{ $user->handicap }}">Dieet aanpassen</button>
    </td>
    <td>
        @if ($user->blocked)
            <strong class=negative>Geblokkeerd</strong>
        @else
            Nee
        @endif
    </td>
    <td>
        @if ($user->blocked)
            <button class="release-user" data-id="{{ $user->id }}">Vrijgeven</button>
        @else
            <button class="block-user" data-id="{{ $user->id }}">Blokkeren</button>
        @endif
    </td>
</tr>
