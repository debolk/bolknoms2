<html>
<body>
    <p>
        Beste <?= $registration->name; ?>,
    </p>
    <p>
        Je hebt je aangemeld op <a href="http://noms.debolk.nl">Bolknoms</a> voor de maaltijd van <?= $meal->longDate(); ?>. Helaas kan de maaltijd niet doorgaan. Je aanmelding is automatisch verwijderd.
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

