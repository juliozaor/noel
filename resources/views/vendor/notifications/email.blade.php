
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


    .card-mail {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        background-color: #EEEEEE;
        padding: 20px;
    }

    .titulo-card h3 {
        color: red;
    }


    .imagen img{
    width: 100%;
  }
  .btn-rojo{
    background: #EE2825 0% 0% no-repeat padding-box !important;
    box-shadow: 0px 3px 10px #0000005C !important;
    border-radius: 20px !important;
    padding: 5px 20px;
    text-decoration: none;
    color: white;
  }
</style>

<body>
    <div class="imagen">
        <img src="https://tysa.co/noel/header.png" alt="">
      </div>

    <div class="card-mail">
        <div class="titulo-card">
          <p>Recibimos su solicitud para recuperar su contraseña.</p><br>
           <p>Por favor no responda este correo y tenga en cuenta las siguientes indicaciones: </p>
            <p><strong>Para continuar con el proceso de recuperación de contraseña, haga clic en el siguiente botón y coloque su nueva contraseña</strong></p>
            <br>
            <a href="{{ $actionUrl }}" class="btn btn-rojo">Recuperar contraseña</a>
        </div>

    </div>


</body>
