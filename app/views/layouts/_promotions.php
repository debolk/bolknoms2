<?php if ($meals->count() > 0): ?>
  <div class="block">
    <h2>Speciale maaltijden</h2>
    <?php foreach ($meals as $meal): ?>
      <?php echo HTML::link(URL::action('Front@inschrijven_specifiek', ['id' => $meal->id]), $meal); ?><br>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
