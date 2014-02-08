<p>
    Beste <?php echo $name; ?>,
</p>
<p>
    Je hebt je aangemeld voor de eettafel op De Bolk op de volgende dagen:
</p>
<ul>
    <?php foreach ($registrations as $registration): ?>
        <li>
            <?php echo $registration->meal; ?>
            (<a href="<?php echo URL::to('/afmelden/{id}/{salt}/', ['id' => $registration->id, 'salt' => $registration->salt]); ?>">afmelden</a>)
        </li>
    <?php endforeach; ?>
</ul>
<p>
  <?php echo Config::get('app.email.footer'); ?>
</p>
