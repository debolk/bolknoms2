<tr class="registration">
    <td>
        <span class="box">&square;</span>
    </td>
    <td class="non_print">
        @if ($registration->username)
            <img class="user-picture" src="/photo/{{ $registration->username }}">
        @else
            &nbsp;
        @endif
    </td>
    <td class="name">
        {{ $registration->name }}
    </td>
    <td class="non_print">
        <?php if ($registration->user_id): ?>
            <i class="fa fa-fw fa-check"></i>
        <?php endif; ?>
    </td>
    <td class="handicap">
        {{ $registration->handicap }}
    </td>
    <td class="non_print">
        <a href="#" class="button remove_registration" data-name="{{ $registration->name }}" data-id="{{ $registration->id }}">Afmelden</a>
    </td>
</tr>
