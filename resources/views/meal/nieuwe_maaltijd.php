<h1>Nieuwe maaltijd toevoegen</h1>

<?php echo App\Http\Helpers\Flash::error_messages_for(Session::get('validation_errors')); ?>

<form action="/administratie/nieuwe_maaltijd_maken" method="post" accept-charset="utf-8">
    <p>
        <label class="label" for="date">Datum</label><br>
        <input type="text" placeholder="<?= date('d-m-Y'); ?>" autofocus name="date">
    </p>
    <p>
        <label class="label" for="locked">Inschrijving sluit</label><br>
        <input type="text" placeholder="15:00" name="locked">
    </p>
    <p>
        <label class="label" for="event">Omschrijving</label><br>
        <input type="text" placeholder="" name="event">
    </p>
    <p>
        <input type="submit" value="Maaltijd toevoegen" />
        of <a href="/administratie">niet toevoegen</a>
    </p>
</form>
