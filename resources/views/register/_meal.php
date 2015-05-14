<div class="meal">
    <button data-id="<?= $meal->id ;?>">nom!</button>
    <span class="date"><?= $meal; ?></span>
    <?php if ($meal->locked != '15:00:00'): ?>
        (aanmelden tot <?= $meal->deadline(); ?>)
    <?php endif; ?>
</div>
