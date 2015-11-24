<?php $user = App\Http\Helpers\OAuth::user(); ?>
@if ($user)
    <p>
        <?php if ($user->handicap): ?>
            Jouw dieetwensen: <?= $user->handicap; ?>.
        <?php else: ?>
            Je hebt geen dieet ingesteld.
        <?php endif; ?>
        <a href="/profiel" title="Wijzigen">Wijzigen</a>
    </p>
@else
    <div class="anonymous">
        <p>
            <strong>Hallo! Welkom bij bolknoms.</strong>
        </p>
        <p>
            Hier kun je je aanmelden voor de eettafel van <a href="http://www.debolk.nl" title="De Bolk">De Bolk</a>. Voordat je kunt mee-eten hebben we wel wat gegevens van je nodig.
        </p>
        <p class="method">
            <a href="/login" class="button" title="Login met je Bolkaccount">Login met je Bolkaccount</a>
            of <a href="#" class="proceed_anonymous" title="aanmelden zonder Bolkaccount">doorgaan zonder Bolkaccount</a>
        </p>
        <form action="#">
            <p>
                <label for="name">Voor- en achternaam</label>
                <input name="name" id="name" autocomplete="off">
            </p>
            <p>
                <label for="email">E-mail</label>
                <input name="email" id="email" autocomplete="off">
            </p>
            <p>
                <label for="handicap">Dieetwensen</label>
                <input name="handicap" id="handicap" autocomplete="off">
            </p>
        </form>
    </div>
@endif
