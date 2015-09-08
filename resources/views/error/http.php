<div class="notification error">
    <h1>Foutmelding (<?= $status; ?>)</h1>
    <?= $message; ?>
</div>

<p>
    Er is een fatale fout opgetreden. U kunt <a href="mailto:<?= env('MAIL_ADMIN_MAIL'); ?>">contact opnemen met de beheerder</a> voor ondersteuning.
</p>
