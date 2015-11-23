@if (count($meals) > 0)
    <table border-spacing=0>
        <thead><tr>
            <th>Datum</th>
            <th>Omschrijving</th>
            <th>Aanmeldingen</th>
            <th>Onbevestigde <br> aanmeldingen</th>
            <th>Aanmelden tot</th>
            <th>Etenstijd</th>
            <th>&nbsp;</th>
        </tr></thead>
        <tbody>
            @each('dashboard/_meal', $meals, 'meal')
        </tbody>
    </table>
@else
    <p class="zero_case">Geen maaltijden gevonden</p>
@endif
