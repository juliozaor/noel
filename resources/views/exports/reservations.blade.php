<table>
    <thead>
        <tr>
            <th>Id reserva</th>
            <th>Fecha</th>
            <th>hora</th>
            <th>Tipo de usuario</th>
            <th>Nombre de Titular</th>
            <th>Identificación Titular</th>
            <th>Correo</th>
            <th>Telefono</th>
            <th>Nombre de miembro</th>
            <th>Identificación miembro</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($reservations as $reservation)
            <tr>
                <td>{{ $reservation['reservation_id'] }}</td>
                <td>{{ $reservation['initial_date'] }}</td>
                <td>{{ $reservation['initial_time'] }}</td>
                <td>{{ $reservation['type'] }}</td>
                <td>{{ $reservation['name_user'] }}</td>
                <td>{{ $reservation['document_user'] }}</td>
                <td>{{ $reservation['email'] }}</td>
                <td>{{ $reservation['cell'] }}</td>
                <td>{{ $reservation['name_member'] }}</td>
                <td>{{ $reservation['document_member'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
