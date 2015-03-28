<h1><?= $meal ?></h1>
<p class="subtitle">
    <?= $meal->registrations()->count(); ?> eters &mdash; sluitingstijd <?= $meal->deadline(); ?>
</p>

<h2>
    Eters
    <img id="print" src="/images/printer.png" alt="Print eterslijst" title="Print eterslijst" width="32" height="32">
</h2>

<ul id="registrations">
    <?php foreach ($meal->registrations()->get() as $r): ?>
        <li>
            <input type="checkbox" /> <span class="name"><?= $r->name; ?></span>
            <?php if (!empty($r->handicap)): ?>
                (<?php echo $r->handicap; ?>)
            <?php endif; ?>
            <img class="remove_registration" data-name="<?= $r->name; ?>" data-id="<?=$r->id;?>" src="/images/cross.png" alt="Eter uitschrijven" title="Eter uitschrijven">
        </li>
    <?php endforeach; ?>
</ul>

<h2>Nieuwe eter toevoegen</h2>
<form action="#new_registration">
    <p>
        <label for="name">Naam</label><br>
        <input type="text" id="name" name="name">
    </p>
    <p>
        <label for="handicap">Handicap</label><br>
        <input type="text" id="handicap" name="handicap">
    </p>
    <p>
        <input type="submit" value="Toevoegen">
    </p>
</form>

<link rel="stylesheet" href="/stylesheets/print.css" media="print" />
