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


    .card {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        background-color: #EEEEEE;
        margin: 10px;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px
    }

    .titulo-card h3 {
        color: red;
    }


    .imagen img{
    width: 100%;
  }
</style>

<body>
    <div class="imagen">
        <img src="https://tysa.co/noel/header.png" alt="">
      </div>

    <div class="card">
        <div class="titulo-card">
           <h3>Reservas para el día: <strong>{{$date}}</strong></h3>
        </div>
    </div>


</body>

</html>