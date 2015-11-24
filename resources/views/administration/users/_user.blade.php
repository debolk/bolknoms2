<tr>
    <td>{{ $user->name }}</td>
    <td>{{ $user->handicap or 'Geen dieet ingesteld' }}</td>
    <td>
        <button>Dieet aanpassen</button>
    </td>
    <td>
        @if ($user->blocked)
            <i class="fa fa-fw fa-check"></i>
            <button>Vrijgeven</button>
        @else
            <i class="fa fa-fw fa-times"></i>
            <button>Blokkeren</button>
        @endif
    </td>
</tr>
