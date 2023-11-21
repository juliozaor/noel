<table>
    <thead>
        <tr>
            <th>destination</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Cupos</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reservations as $reservation)
            <tr>
                <td>{{ $reservation['destination'] }}</td>
                <td>{{ $reservation['Fecha'] }}</td>
                <td>{{ $reservation['Hora'] }}</td>
                <td>{{ $reservation['Cupos'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
