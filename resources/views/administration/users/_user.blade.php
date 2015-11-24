<tr>
    <td>{{ $user->name }}</td>
    <td class="handicap">{{ $user->handicap or 'Geen dieet ingesteld' }}</td>
    <td>
        <button class="edit-handicap" data-id="{{ $user->id }}" data-handicap="{{ $user->handicap }}">Dieet aanpassen</button>
    </td>
    <td>
        @if ($user->blocked)
            <i class="fa fa-fw fa-check"></i>
            <button class="release-user" data-id="{{ $user->id }}">Vrijgeven</button>
        @else
            <i class="fa fa-fw fa-times"></i>
            <button class="block-user" data-id="{{ $user->id }}">Blokkeren</button>
        @endif
    </td>
</tr>
