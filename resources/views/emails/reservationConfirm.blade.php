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

    .encabeado {
        height: 55px;
        background-color: red;
        color: white;
        display: flex;
        align-items: center;
    }

    .encabeado span {
        margin-left: 20px;
        font-weight: 600;
    }

    .card-qr {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        background-color: #EEEEEE;
        margin: 10px;
        border-radius: 12px;
        padding: 20px;
    }

    .titulo-card h3 {
        color: red;
    }

    .card-body {
        margin: 10px 20px;
        display: flex;
        flex-wrap: wrap;
    }

    .qr {
        padding: 10px;
        border-radius: 5px;
        background-color: white;
        max-width: 200px;
    }

    .titulo-qr {
        font-weight: 400;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .footer {
        margin: 20px 25px
    }
    .imagen img{
    width: 100%;
  }
</style>

<body>
    <div class="imagen">
        <img src="https://tysa.co/noel/header.png" alt="">
      </div>
    <div class="encabeado">
        @foreach ($codes as $code)
            @if ($code['isUser'] == 1)
                <span>Hola, {{ $code['name'] }}</span>
            @endif
        @endforeach


    </div>

    <div class="card-qr">
        <div class="titulo-card">
            @foreach ($codes as $code)
                @if ($code['isUser'] == 1)
                    <h3> Información de tu reserva</h3>
                    <p>{{ $code['quota'] }} cupos</p>
                    <p><strong>Fecha: {{ $code['date'] }} - Hora: {{ $code['time'] }}</strong></p>
                    <p>Imprime o presenta el codigo QR correspondiente a cada miembro</p>
                @endif
            @endforeach
        </div>
    </div>
    <div class="card-body">
        @foreach ($codes as $code)
            <div class="qr">
                <div class="titulo-qr">
                    {{ $code['name'] }}
                </div>
                <div class="imagen-qr">
                    {{-- <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::size(150)->generate($code['qr'])) }}" alt="Código QR"> --}}

                    {{-- {!! QrCode::size(150)->generate($code['qr']) !!} --}}
                  {{-- <img src="{{ $message->embed(public_path($code['qr'])) }}" alt="Código QR"> --}}
                  <img src="{{public_path($code['qr'])}}" alt="{{ $code['name'] }}">


                </div>

            </div>
        @endforeach
    </div>

    <div class="footer">
        <p><strong>RECOMENDACIONES DE INGRESO</strong></p>
        <p>Para disfrutar de esta divertida experiencia de forma segura, ten en cuenta las siguientes recomendaciones:
        </p>
        <p>*Esta experiencia será realizada en Mundo NOEL, en las instalaciones de la Planta de Producción de Compañía
            De
            Galletas NOEL S.A.S. ubicada en la Carrera 52 # 2 - 28 en Medellín, Colombia. </p>
        <p>*Este pase de ingreso no tiene valor comercial, es personal e intransferible y será requerido de forma
            digital o
            impreso por nuestro personal de seguridad, para facilitar tu ingreso a mi Compañía.</p>
        <p>*Debes estar en la portería número 4 de NOEL, ubicada sobre la calle #4, con mínimo 30 minutos de antelación
            porque
            el recorrido empieza puntualmente. *La apertura de puertas y el ingreso de los visitantes, se realizará 15
            minutos
            antes de la hora de inicio de cada recorrido.</p>
        <p>*En caso de que tu visita sea suspendida o cancelada por temas de fuerza mayor o caso fortuito, te informaré
            con
            antelación al correo electrónico registrado en la inscripción. *Te invito a utilizar transporte público y
            alternativas de transporte sostenible, porque no se permitirá el ingreso de vehículos al interior de la
            Compañía, ni
            tampoco podrán estacionarse en la calle #4.</p>
        <p>*Si vienes en vehículo propio, podrás estacionarlo en el parqueadero de Coltabaco, ubicado en la Cra 50 No. 5
            -
            175, y en el Parqueadero habilitado ubicado en la Calle #2.</p>

    </div>
</body>

</html>
