<?php if ($errors->count() > 0): ?>
    <div class="notification error">
        <p><strong>De wijzigingen konden niet worden opgeslagen:</strong></p>
        <ul>
            <?php foreach ($errors->getBag('default')->all() as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>
