<tr class="registration">
    <td>
        <span class="box">&square;</span>
    </td>
    <td class="name">
        <?= $registration->name; ?>
    </td>
    <td class="handicap">
        <?= $registration->handicap; ?>
    </td>
    <td>
        <img class="remove_registration"
             data-name="<?= $registration->name; ?>"
             data-id="<?=$registration->id;?>"
             src="/images/cross.png"
             alt="Eter uitschrijven" title="Eter uitschrijven">
    </td>
</tr>
