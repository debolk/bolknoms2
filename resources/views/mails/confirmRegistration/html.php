<html>
<body>
    <p>
        Beste <?= $registration->name; ?>,
    </p>
    <p>
        Je hebt je aangemeld op <a href="<?= url('/'); ?>">Bolknoms</a> voor de maaltijd van <?= $registration->longDate(); ?>. Omdat je geen lid bent (of niet was ingelogd) moet je deze aanmelding bevestigen per e-mail. Gebruik hiervoor onderstaande link:
    </p>
    <p>
        <a href="<?= url('bevestigen', [$registration->id, $registration->salt]); ?>">Aanmelding bevestigen</a>
    </p>
    <p>
        <small>
            Met vriendelijke groet,<br>
            Commissaris Maaltijden <br>
            De Bolk (D.S.V. "Nieuwe Delft") <br>
            E-mail: <a href="mailto:maaltijdcom@nieuwedelft.nl">maaltijdcom@nieuwedelft.nl</a> <br>
            Telefoon: <a href="tel:+31152126012">+31 15 212 6012</a>
        </small>
    </p>
</body>
</html>

