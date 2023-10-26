
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>correo</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
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

    <div style="display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    background-color: #EEEEEE;
    padding: 20px;">
        <div>
          <p>Recibimos su solicitud para recuperar su contraseña.</p><br>
           <p>Por favor no responda este correo y tenga en cuenta las siguientes indicaciones: </p>
            <p><strong>Para continuar con el proceso de recuperación de contraseña, haga clic en el siguiente botón y coloque su nueva contraseña</strong></p>
            <br>
            <a href="{{ $actionUrl }}" style="background: #EE2825 0% 0% no-repeat padding-box !important;
            box-shadow: 0px 3px 10px #0000005C !important;
            border-radius: 20px !important;
            padding: 5px 20px;
            text-decoration: none;
            color: white;">Recuperar contraseña</a>
        </div>

    </div>


</body>
