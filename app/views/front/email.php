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
            (<?php echo link_to_route('afmelden', 'afmelden', ['id' => $registration->id, 'salt' => $registration->salt]); ?>)
        </li>
    <?php endforeach; ?>
</ul>
<p>
  <?php echo Config::get('app.email.footer'); ?>
</p>
