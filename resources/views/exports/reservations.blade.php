<table>
    <thead>
        <tr>
            <th>destination</th>
            <th>Fecha</th>
            <th>Hora</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reservations as $reservation)
            <tr>
                <td>{{ $reservation['destination'] }}</td>
                <td>{{ $reservation['Fecha'] }}</td>
                <td>{{ $reservation['Hora'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
