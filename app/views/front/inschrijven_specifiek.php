<h2>Aanmelden voor maaltijd</h2>
<?php echo Flash::display_messages(); ?>

<?php echo Hform::error_messages_for(Session::get('validation_errors')); ?>

<?php echo Form::open(['url' => URL::route('aanmelden_specifiek', ['id' => $meal->id])]); ?>
    <p>
        <label for="date" class="label">Eettafel</label>
        <?php echo $meal; ?>
    </p>
    <?php if ($meal->open_for_registrations()): ?>
        <p>
            <label for="name" class="label">Naam</label>
            <?php echo Form::text('name', Input::old('name'), ['maxlength' => 30]); ?>
            <small>Gebruik je volledige voor- en achternaam. Onduidelijke inschrijvingen worden vernietigd.</small>
        </p>
        <p>
	        <label for="email" class="label">E-mail</label>
	        <?php echo Form::text('email', Input::old('email')); ?>
	    </p>
	    <p>
	        <label for="handicap" class="label">Handicap</label>
	        <?php echo Form::text('handicap', Input::old('handicap')); ?>
	        <small>Bijvoorbeeld vegetari&euml;r, geen pinda's, etc..</small>
	    </p>
        <p>
            <input type="submit" value="Aanmelden"/>
        </p>    
    <?php else: ?>
        <p class="notification warning">
            Sorry, de deadline is verstreken. Je kunt je niet meer aanmelden voor de maaltijd.
        </p>
    <?php endif; ?>
</form>

<?php echo View::make('front/_afmelden'); ?>
<?php echo View::make('front/_spelregels'); ?>
<?php echo View::make('layouts/_navigation'); ?>
