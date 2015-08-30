<tr data-id="<?php echo $meal->id; ?>">
	<td class="date">
        <a href="/administratie/<?php echo $meal->id; ?>">
            <?php echo $meal->longDate(); ?>
        </a>
    </td>
    <td><?=$meal->event;?></td>
    <td class="number"><?php echo $meal->registrations()->confirmed()->count(); ?></td>
	<td class="number"><?php echo $meal->registrations()->unconfirmed()->count(); ?></td>
    <td class="date <?= !$meal->normalDeadline() ? 'attention' : ''; ?>">
        <?= $meal->deadline(); ?>
    </td>
    <td class="date <?= !$meal->normalMealTime() ? 'attention' : ''; ?>">
        <?= strftime("%H:%M", strtotime($meal->mealtime)); ?>
    </td>
	<td>
		</a>
		<a href="/administratie/verwijder/<?php echo $meal->id; ?>" class="destroy-meal" title="Verwijderen">
			<img src="/images/cross.png" alt="Verwijderen"/>
		</a>
	</td>
</tr>
