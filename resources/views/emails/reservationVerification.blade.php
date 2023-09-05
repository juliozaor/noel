<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>correo</title>
</head>

<body>
    <h1>correo electronico</h1>
    <p>prueba de confirmación</p>
  {{--   {!! Form::open(['route' => ['api.reservations.update', $reservationId ], 'method' => 'put']) !!}
    {!! Form::submit('Confirmar reserva', ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!} --}}
    <a href="{{route('api.reservations.confirmet', ['reservation' => $reservationId ] )}}" target="_blank">
      Confirmar reserva
    </a>
</body>

</html>
