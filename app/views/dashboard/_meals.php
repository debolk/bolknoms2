<?php if (count($meals) > 0): ?>
    <table border-spacing=0>
        <thead><tr>
            <th>Datum</th>
            <th>Omschrijving</th>
            <th>Eters</th>
            <th>Sluitingstijd</th>
            <th>&nbsp;</th>
        </tr></thead>
        <tbody>
            <?php foreach ($meals as $meal): ?>
                <?php echo View::make('dashboard/_meal',array('meal' => $meal)); ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="zero">Geen maaltijden gevonden</p>
<?php endif; ?>
