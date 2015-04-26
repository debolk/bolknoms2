<h1>Top 100 eters dit collegejaar</h1>
<ol>
    <?php foreach ($statistics_ytd as $registration): ?>
        <li><?php echo $registration->name; ?> (<?php echo $registration->count;?>)</li>
    <?php endforeach; ?>
</ol>

<h1>Top 100 eters all-time</h1>
<ol>
    <?php foreach ($statistics_alltime as $registration): ?>
        <li><?php echo $registration->name; ?> (<?php echo $registration->count;?>)</li>
    <?php endforeach; ?>
</ol>
