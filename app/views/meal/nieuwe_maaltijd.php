<h1>Nieuwe maaltijd toevoegen</h1>

<?php echo Hform::error_messages_for(Session::get('validation_errors')); ?>

<form action="/administratie/nieuwe_maaltijd_maken" method="post" accept-charset="utf-8" class="clearfix">
    <p>
       <label class="label" for="date">Datum</label>
       <?php echo Form::text('date', Input::old('date'), ['class' => 'datepicker']); ?>
    </p>
    <p>
       <label class="label" for="locked">Inschrijving sluit</label>
       <?php echo Form::text('locked', Input::old('locked', '15:00')); ?>
    </p>
    <p>
      <label for="event" class="label">Evenement</label>
       <?php echo Form::text('event', Input::old('event')); ?>
    </p>
    <p>
      <label for="promoted" class="label">Extra promotie</label>
      <?php echo Form::checkbox('promoted', true, Input::old('promoted')); ?>
    </p>
    <p>
        <input type="submit" value="Maaltijd toevoegen" />
    </p>
</form>

<?php echo View::make('application/_navigation'); ?>
