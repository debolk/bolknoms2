<tr data-id="<?php echo $meal->id; ?>">
	<td class="date">
        <a href="/administratie/<?php echo $meal->id; ?>">
            <?php echo $meal; ?>
        </a>
    </td>
    <td><?=$meal->event;?></td>
	<td class="number"><?php echo $meal->registrations->count(); ?></td>
    <td class="date"><?= date('H:i', strtotime($meal->locked));?></td>
    <td class="date"><?= date('H:i', strtotime($meal->mealtime));?></td>
	<td>
		</a>
		<a href="/administratie/verwijder/<?php echo $meal->id; ?>" class="destroy-meal" title="Verwijderen">
			<img src="/images/cross.png" alt="Verwijderen"/>
		</a>
	</td>
</tr>
