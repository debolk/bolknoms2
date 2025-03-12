<tr class="registration">
    <td>
        <span class="box">&square;</span>
    </td>
    <td class="name">
        {{ $registration->name }}
    </td>
    <td class="non_print">
        @if ($registration->user_id)
            <i class="fa fa-fw fa-check"></i>&nbsp;Bolker
        @elseif ($registration->confirmed)
            <i class="fa fa-fw fa-check"></i>&nbsp;per&nbsp;<a href="mailto:{{ $registration->email }}">e-mail</a>
        @else
            Niet bevestigd
        @endif
    </td>
    <td class="handicap">
        {{ $registration->handicap }}
    </td>
    <td class="non_print">
        <a href="#" class="button remove_registration" data-name="{{ $registration->name }}"
            data-id="{{ $registration->id }}">Afmelden</a>
    </td>
</tr>
