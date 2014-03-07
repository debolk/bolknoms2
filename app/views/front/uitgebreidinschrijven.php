<?php echo View::make('front/_introductie'); ?>

<?php echo Flash::display_messages(); ?>

<?php echo Hform::error_messages_for(Session::get('validation_errors')); ?>

<h2>Uitgebreid aanmelden</h2>
<form action="/uitgebreidaanmelden" method="post" accept-charset="utf-8" class="clearfix">
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
        <span class="label">Eettafels</span>
        <?php if (count($meals) > 0): ?>
            <table>
                <thead>
                <tr>
                    <th class="checkbox"><input type="checkbox" name="all-meals"></th>
                    <th>Datum</th>
                    <th>Aanmeldingen</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($meals as $meal): ?>
                        <tr>
                            <td class="checkbox">
                                <?php echo Form::checkbox('meals[]', $meal->id, in_array($meal->id, Input::old('meals', []))); ?>
                            </td>
                            <td class="date"><?php echo $meal; ?></td>
                            <td class="number"><?php echo $meal->registrations->count(); ?></td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        <?php else: ?>
            <span class="zero">Er zijn geen maaltijden beschikbaar om je voor aan te melden.</span>
        <?php endif; ?>
    </p>
    <p>
        <input type="submit" id="submit" value="Aanmelden"/>
    </p>
</form>

<?php echo View::make('front/_afmelden'); ?>
<?php echo View::make('front/_spelregels'); ?>
<?php echo View::make('layouts/_navigation'); ?>
