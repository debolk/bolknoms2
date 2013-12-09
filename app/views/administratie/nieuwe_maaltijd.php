<h1>Nieuwe maaltijd toevoegen</h1>

<?php echo Hform::error_messages_for(Session::get('validation_errors')); ?>

<form action="/administratie/nieuwe_maaltijd_maken" method="post" accept-charset="utf-8" class="clearfix">
    <p>
       <label class="label" for="date">Datum</label>
       <input type="text" name="date" id="date" class="datepicker">
    </p>
    <p>
       <label class="label" for="locked">Inschrijving sluit</label>
       <input type="text" name="locked" value="15:00">
    </p>
    <p>
      <label for="event" class="label">Evenement</label>
      <input type="text" name="event">
    </p>
    <p>
      <label for="promoted" class="label">Extra promotie</label>
      <input type="checkbox" name="promoted" value="1">
    </p>
    <p>
        <input type="submit" value="Maaltijd toevoegen" />
    </p>
</form>

<?php echo View::make('layouts/_navigation'); ?>
