<h1>Nieuwe maaltijd toevoegen</h1>

<?php echo App\Http\Helpers\Flash::error_messages_for(Session::get('validation_errors')); ?>

<form action="/administratie/nieuwe_maaltijd_maken" method="post" accept-charset="utf-8">
    <p>
        <label class="label" for="meal_timestamp">Datum en tijd</label><br>
        <input type="text" placeholder="<?= date('d-m-Y 18:30'); ?>" autofocus name="meal_timestamp">
    </p>
    <p>
        <label class="label" for="locked_timestamp">Inschrijving sluit op</label><br>
        <input type="text" placeholder="<?= date('d-m-Y 15:00'); ?>" name="locked_timestamp">
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
