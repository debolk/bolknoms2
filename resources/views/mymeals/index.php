<h1>Mijn maaltijden</h1>
<p>
    Een lijst met al je maaltijden.
</p>

<div class="meals">
    <?php foreach ($registrations as $registration): ?>
        <?= $registration->meal; ?> <br>
    <?php endforeach; ?>
</div>
