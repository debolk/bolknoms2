<h1>Aanmelden voor maaltijden</h1>

<?php if (isset($user)): ?>
    <?= view('register/_user', ['user' => $user]); ?>
<?php else: ?>
    <?= view('register/_no_user'); ?>
<?php endif; ?>


<form action="#" id="register_form">
    <?php if (count($meals) == 0): ?>
        <p class="empty">Er zijn geen maaltijden open waarvoor je je kunt aanmelden.</p>
    <?php endif; ?>
    <div class="meals">
        <?php foreach ($meals as $meal): ?>
            <?= view('register/_meal', ['meal' => $meal]); ?>
        <?php endforeach; ?>
    </div>
</form>
