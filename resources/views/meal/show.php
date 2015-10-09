<h1>Aftekenlijst <?= $meal ?></h1>
<p class="subtitle">
    <span id="count"><?= $meal->registrations()->confirmed()->count(); ?></span> eters
    &mdash;
    aanmelden tot <?= $meal->deadline(); ?>
    &mdash;
    etenstijd <?= date('H:i', strtotime($meal->meal_timestamp)); ?> uur
    <span class="non_print">
        &mdash;
        <a href="/administratie/<?=$meal->id;?>/edit">maaltijd bewerken</a>
    </span>
</p>

<h2>
    Bevestigde aanmeldingen
    <i id="print" class="fa fa-fw fa-2x fa-print"></i>
</h2>

<p id="print_instructions">
    Bonnetjes opgehaald door: _____________________________
</p>

<table id="registrations">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>Naam</th>
            <th>Bolkaccount</th>
            <th>Dieet</th>
            <th><span class="non_print">Verwijderen </span></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($meal->registrations()->confirmed()->get() as $registration): ?>
            <?= View::make('meal/_registration', ['registration' => $registration]); ?>
        <?php endforeach; ?>
    </tbody>
</table>

<?= view('meal/_unconfirmed_list', ['meal' => $meal]); ?>

<h2>Nieuwe eter toevoegen</h2>
<form action="#new_registration" id="new_registration" data-meal_id="<?= $meal->id; ?>">
    <p>
        <label for="user_id">Bolker</label><br>
        <select name="user_id" id="user_id">
            <?php foreach ($users as $user): ?>
                <option value="<?= $user->id; ?>"><?= $user->name; ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <input type="submit" value="Toevoegen">
    </p>
</form>
<p class="non_print">
    <a href="#" id="subscribe_anonymous" data-meal_id="<?= $meal->id; ?>">Externe aanmelden</a>
</p>

<link rel="stylesheet" href="/stylesheets/print.css" media="print" />
