<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Cupos Registrados</th>
            <th>Cupos Disponibles</th>
            <th>Cupos Reservados</th>
            <th>Cupos Reservados por Colaboradores</th>
            <th>Asistencias</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reportData as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->fecha }}</td>
                <td>{{ $row->hora }}</td>
                <td>{{ $row->cupos_registrados }}</td>
                <td>{{ $row->cupos_disponibles }}</td>
                <td>{{ $row->cupos_reservados }}</td>
                <td>{{ $row->cupos_reservados_colaboradores}}</td>
                <td>{{ $row->asistencias }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
