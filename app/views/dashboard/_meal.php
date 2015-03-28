<tr class="meal" data-id="<?php echo $meal->id; ?>">
	<td class="date">
        <a href="/administratie/<?php echo $meal->id; ?>">
            <?php echo $meal; ?>
        </a>
    </td>
	<td class="number"><?php echo $meal->registrations->count(); ?></td>
    <td class="date"><?php echo $meal->deadline(); ?></td>
	<td>
		</a>
		<a href="/administratie/verwijder/<?php echo $meal->id; ?>" class="destroy-meal" title="Verwijderen">
			<img src="/images/cross.png" alt="Verwijderen"/>
		</a>
	</td>
</tr>
