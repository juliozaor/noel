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
 
    <div class="visible-print text-center">
      {!! QrCode::size(100)->generate($reservationId ); !!}
  </div>
</body>

</html>
