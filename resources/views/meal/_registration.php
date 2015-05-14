<li class="registration">
    <span class="box">&square;</span> <span class="name"><?= $registration->name; ?></span>
    <?php if (!empty($registration->handicap)): ?>
        (<?php echo $registration->handicap; ?>)
    <?php endif; ?>
    <img class="remove_registration" data-name="<?= $registration->name; ?>" data-id="<?=$registration->id;?>" src="/images/cross.png" alt="Eter uitschrijven" title="Eter uitschrijven">
</li>
