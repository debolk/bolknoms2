<tr>
    <td>{{ $user->name }}</td>
    <td>{{ $user->handicap or 'Geen dieet ingesteld' }}</td>
    <td>
        <button>Dieet aanpassen</button>
    </td>
    <td>
        @if ($user->blocked)
            Ja
            <button>Vrijgeven</button>
        @else
            Nee
            <button>Blokkeren</button>
        @endif
    </td>
</tr>
