<tr data-id="{{ $meal->id }}">
	<td class="date">
        <a href="/administratie/{{ $meal->id }}">
            {{ $meal->longDate() }}
        </a>
    </td>
    <td>{{ $meal->event }}</td>
    <td class="number">{{ $meal->registrations()->confirmed()->count() }}</td>
	<td class="number">{{ $meal->registrations()->unconfirmed()->count() }}</td>
    <td class="date {{ !$meal->normalDeadline() ? 'attention' : '' }}">
        {{ $meal->deadline() }}
    </td>
    <td class="date {{ !$meal->normalMealTime() ? 'attention' : '' }}">
        {{ strftime("%H:%M", strtotime($meal->meal_timestamp)) }} uur
    </td>
	<td>
		</a>
		<a href="/administratie/verwijder/{{ $meal->id; }}" class="confirm-intent" title="Verwijderen">
            <i class="fa fa-fw fa-times"></i>
		</a>
	</td>
</tr>
