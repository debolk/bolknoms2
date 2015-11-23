<tr class="registration">
    <td>
        <span class="box">&square;</span>
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
        <i class="fa fa-fw fa-times remove_registration" data-name="{{ $registration->name }}" data-id="{{ $registration->id }}"></i>
    </td>
</tr>
