<h1>Maaltijd bewerken</h1>

<?php echo Hform::error_messages_for(Session::get('validation_errors')); ?>

<form action="/administratie/update/<?php echo $meal->id; ?>" method="post" accept-charset="utf-8" class="clearfix">
    <p>
       <label class="label" for="date">Datum</label>
       <?php echo Form::text('date', $meal->date, ['class' => 'datepicker']); ?>
    </p>
    <p>
       <label class="label" for="locked">Inschrijving sluit</label>
       <?php echo Form::text('locked', strftime('%H:%M', strtotime($meal->locked))); ?>
    </p>
    <p>
      <label for="event" class="label">Evenement</label>
      <?php echo Form::text('event', $meal->event); ?>
    </p>
    <p>
      <label for="promoted" class="label">Extra promotie</label>
      <?php echo Form::checkbox('promoted', true, $meal->promoted); ?>
    </p>
    <p>
        <input type="submit" value="Maaltijd bewerken" />
    </p>
</form>

<?php echo View::make('application/_navigation'); ?>
