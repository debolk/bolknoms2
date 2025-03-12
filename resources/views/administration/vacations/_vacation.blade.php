<tr>
    <td>{{ $vacation->start->isoFormat('%e %B %Y') }}</td>
    <td>{{ $vacation->end->isoFormat('%e %B %Y') }}</td>
    <td>
        <form action="{{ route('vacations.destroy', $vacation->id) }}" method="POST">
            @method('DELETE')
            <button type="submit">Verwijderen</button>
        </form>
    </td>
</tr>
