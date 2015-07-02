<h1>Aanmelden voor maaltijden</h1>

<?php $user = App\Http\Helpers\OAuth::user(); ?>
<?php if ($user): ?>
    <p>
        <?php if ($user->handicap): ?>
            Jouw dieetwensen: <?= $user->handicap; ?>.
        <?php else: ?>
            Je hebt geen dieet ingesteld.
        <?php endif; ?>
        <a href="/profiel" title="Wijzigen">Wijzigen</a>
    </p>

    <?php
        $meal = App\Models\Meal::today()->first();
        if ($meal && !$meal->open_for_registrations() && $user->registeredFor($meal)): ?>
        <div class="notification success">
            <img src="/images/tick.png" alt="">
            Ja, je bent aangemeld voor vandaag.
        </div>
    <?php endif; ?>
<?php else: ?>
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
<?php endif; ?>

<form action="#" id="register_form">
    <?php if (count($meals) == 0): ?>
        <p class="zero_case">Er zijn geen maaltijden open waarvoor je je kunt aanmelden.</p>
    <?php endif; ?>
    <div class="meals">
        <?php foreach ($meals as $meal): ?>
            <?php if (isset($user)): ?>
                <?= view('register/_meal', ['meal' => $meal, 'user' => $user]); ?>
            <?php else: ?>
                <?= view('register/_meal', ['meal' => $meal]); ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</form>
