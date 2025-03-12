<tr>
    <td>{{ $vacation->start->isoFormat('D MMMM YYYY') }}</td>
    <td>{{ $vacation->end->isoFormat('D MMMM YYYY') }}</td>
    <td>
        <form action="{{ route('vacations.destroy', $vacation->id) }}" method="POST">
            @method('DELETE')
            <button type="submit">Verwijderen</button>
        </form>
    </td>
</tr>
