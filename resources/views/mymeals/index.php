<h1>Mijn maaltijden</h1>

<?php if (count($registrations) > 0): ?>
    <div class="meals">
        <?php foreach ($registrations as $registration): ?>
            <?= $registration->meal; ?> <br>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p class="zero">Je hebt je nog nooit aangemeld voor een maaltijd.</p>
<?php endif; ?>
