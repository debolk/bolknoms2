<h1>Maaltijd bewerken</h1>

<?php echo App\Http\Helpers\Flash::error_messages_for(Session::get('validation_errors')); ?>

<form action="/administratie/<?=$meal->id;?>" method="post" accept-charset="utf-8">
    <p>
        <label class="label" for="date">Datum</label><br>
        <input type="text" name="date" value="<?=date('d-m-Y', strtotime($meal->date));?>">
    </p>
    <p>
        <label class="label" for="locked">Inschrijving sluit</label><br>
        <input type="text" value="<?=date('H:i', strtotime($meal->locked));?>" name="locked">
    </p>
    <p>
        <label class="label" for="event">Omschrijving</label><br>
        <input type="text" name="event" value="<?=$meal->event;?>">
    </p>
    <p>
        <input type="submit" value="Wijzigingen opslaan" />
        of <a href="/administratie/<?=$meal->id;?>">niet opslaan</a>
    </p>
</form>
