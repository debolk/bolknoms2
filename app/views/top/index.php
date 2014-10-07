<h1>Top eters dit jaar (<?= sizeof($statistics_ytd); ?> eters)</h1>
<ol>
    <?php foreach ($statistics_ytd as $registration): ?>
        <li><?php echo $registration->name; ?> (<?php echo $registration->count;?>)</li>
    <?php endforeach; ?>
</ol>

<h1>Top eters all-time (<?= sizeof($statistics_alltime); ?> eters)</h1>
<ol>
    <?php foreach ($statistics_alltime as $registration): ?>
        <li><?php echo $registration->name; ?> (<?php echo $registration->count;?>)</li>
    <?php endforeach; ?>
</ol>

<?php echo View::make('application/_navigation'); ?>
