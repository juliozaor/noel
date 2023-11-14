<table>
    <thead>
        <tr>
            @foreach($reportData->first() as $key => $value)
                <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($reportData as $row)
            <tr>
                @foreach($row as $value)
                    <td>{{ $value }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>