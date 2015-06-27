<a href="<?= $url; ?>" <?= $current ? 'class=current' : ''; ?>>
    <?= file_get_contents(public_path() . "/images/$icon.svg"); ?>
    <?= $text; ?>
</a>
