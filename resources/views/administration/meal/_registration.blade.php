<tr class="registration">
    <td>
        <span class="box">&square;</span>
    </td>
    <td>
        @if ($registration->username)
            <img class="user-picture" src="{{ action('OAuth@photoFor', $registration->username) }}">
        @else
            &nbsp;
        @endif
    </td>
    <td class="name">
        {{ $registration->name }}
    </td>
    <td>
        <?php if ($registration->user_id): ?>
            <i class="fa fa-fw fa-check"></i>
        <?php endif; ?>
    </td>
    <td class="handicap">
        {{ $registration->handicap }}
    </td>
    <td>
        <a href="#" class="button remove_registration" data-name="{{ $registration->name }}" data-id="{{ $registration->id }}">Afmelden</a>
    </td>
</tr>
