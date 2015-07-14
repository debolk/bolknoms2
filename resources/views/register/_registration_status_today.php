<?php
    $meal = App\Models\Meal::today()->first();
    $user = \App\Http\Helpers\OAuth::user();

    if (!$meal || !$user) {
        return;
    }
?>
<?php if ($meal && !$meal->open_for_registrations() && $user->registeredFor($meal)): ?>
    <div class="notification success">
        <img src="/images/tick.png" alt="">
        Ja, je bent aangemeld voor vandaag.
    </div>
<?php endif; ?>
