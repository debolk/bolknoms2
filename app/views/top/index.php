<h1>Top eters dit jaar</h1>
<ol>
    <?php foreach ($statistics_ytd as $registration): ?>
        <li><?php echo $registration->name; ?> (<?php echo $registration->count;?>)</li>
    <?php endforeach; ?>
</ol>

<h1>Top eters dit all-time</h1>
<ol>
    <?php foreach ($statistics_ytd as $registration): ?>
        <li><?php echo $registration->name; ?> (<?php echo $registration->count;?>)</li>
    <?php endforeach; ?>
</ol>

<?php echo View::make('application/_navigation'); ?>
