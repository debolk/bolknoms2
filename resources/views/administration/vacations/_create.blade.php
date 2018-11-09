<form action="{{ route('vacations.store') }}" method="POST">
    <tr>
        <td>
            <input type="date" name="start" required>
        </td>
        <td>
            <input type="date" name="end" required>
        </td>
        <td>
            <button type="submit">Vakantie toevoegen</button>
        </td>
    </tr>
</form>
