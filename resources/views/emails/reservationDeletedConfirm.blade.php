<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>correo</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: Montserrat, Helvetica, sans-serif;
  }
</style>

<body>
  <div>
    <img src="{{ asset('/assets/img/header.png') }}" alt="" style="width: 100%;">
  </div>
  <div style="background: #EE2825; color: #fff;">
    <span style="margin-left: 20px; font-weight: 600;font-size: 24px;">Hola, {{ $reservation['name'] }}</span>
  </div>

  <div style="background-color: #EEEEEE;margin: 10px;border-radius: 12px;padding: 20px;">
    <div>
      <div style="display: flex;
                text-align: center;
                align-items: center;
                justify-content: center;">
        <div style="text-align: center; margin-right: 30px; margin-left: 20px;">
          <h3 style="color:red;margin: 0;padding: 0;font-size: 26px;">Tu reserva fue Cancelada</h3>
          <p style="font-size: 20px;margin: 0;">
            Tu reserva de <strong>{{$reservation['quota']}} cupos</strong> para el día: <strong>{{ date('d-m-Y', strtotime($reservation['date'])) }} a las {{ date('h:i A', strtotime($reservation['time'])) }}</strong> fue cancelada y por lo tanto los QR enviados anteriormente dejarán de funcionar.
          </p>
        </div>
      </div>
    </div>
  </div>
</body>

</html>