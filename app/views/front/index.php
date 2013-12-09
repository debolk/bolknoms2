<?php echo View::make('front/_introductie'); ?>

<h2>Snel aanmelden</h2>
<?php echo Flash::display_messages(); ?>

<?php echo Hform::error_messages_for(Session::get('validation_errors')); ?>

<?php if ($upcoming_meal): ?>
    <form action="/aanmelden" method="post" accept-charset="utf-8" class="clearfix">
        <p>
            <label for="date" class="label">Volgende eettafel</label>
            <?php echo $upcoming_meal; ?>
            <?php if (! $upcoming_meal->today()): ?>
                <br>
                <span class="warning">Let op: deze maaltijd is niet vandaag!</span>
            <?php endif; ?>
        </p>
        <p>
            <label for="name" class="label">Naam</label>
            <input type="text" name="name" value="" />
            <small>Gebruik je volledige voor- en achternaam. Onduidelijke inschrijvingen worden vernietigd.</small>
        </p>
        <p>
            <input type="submit" value="Aanmelden"/>
        </p>    
        <p>
            Wil je je aanmelden voor meerdere dagen tegelijkertijd, je vrienden meenemen, of 
            heb je speciale eisen m.b.t. voedsel? Schrijf je dan in via
            <a href="/uitgebreid-inschrijven">uitgebreid aanmelden</a>.
        </p>
    </form>
<?php else: ?>
    <p class="notification warning">
        Er is geen volgende eettafel ingepland.
    </p>
<?php endif; ?>

<?php echo View::make('front/_spelregels'); ?>

<h2>Nog sneller aanmelden</h2>
<p>
    Gebruik je Google Chrome? Installeer dan de gratis
    <a href="https://chrome.google.com/webstore/detail/cpofokaclgokgfcalaiodpkjkhafahfe/">bolknoms-app</a>!
</p>

<?php echo View::make('layouts/_navigation'); ?>