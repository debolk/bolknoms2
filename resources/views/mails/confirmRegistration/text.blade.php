Beste {{ $registration->name }},

Je hebt je aangemeld op Bolknoms ({{ url('/') }}) voor de maaltijd van {{ $registration->longDate() }}.
Omdat je geen lid bent (of niet was ingelogd) moet je deze aanmelding bevestigen per e-mail.
Bezoek hiervoor onderstaand adres in je browser:

{{ url('bevestigen', [$registration->id, $registration->salt]) }}

Met vriendelijke groet,
Commissaris Maaltijden
De Bolk (D.S.V. "Nieuwe Delft")
E-mail: maaltijdcom@nieuwedelft.nl
Telefoon: +31 15 212 6012
