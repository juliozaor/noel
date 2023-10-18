<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>correo</title>
</head>

<body>

    @foreach ($codes as $code)
        @if ($code['isUser'] == 1)
            <h1>Hola, {{ $code['name'] }}</h1>
        @endif
    @endforeach
    <div class="visible-print text-center">
<div class="container">
        @foreach ($codes as $code)
        <div class="row">
                <div class="col">
                    {{ $code['name'] }}
                </div>
                <div class="col">
                    {!! QrCode::size(150)->generate($code['qr']) !!}

                </div>
           
          </div>
        @endforeach
      </div>
    </div>
    <br>
   <p>RECOMENDACIONES DE INGRESO </p> 
   <p>Para disfrutar de esta divertida experiencia de forma segura, ten en cuenta las siguientes recomendaciones: </p> 
   <p>*Esta experiencia será realizada en Mundo NOEL, en las instalaciones de la Planta de Producción de Compañía De Galletas NOEL S.A.S. ubicada en la Carrera 52 # 2 - 28 en Medellín, Colombia. </p> 
   <p>*Este pase de ingreso no tiene valor comercial, es personal e intransferible y será requerido de forma digital o impreso por nuestro personal de seguridad, para facilitar tu ingreso a mi Compañía.</p> 
   <p>*Debes estar en la portería número 4 de NOEL, ubicada sobre la calle #4, con mínimo 30 minutos de antelación porque el recorrido empieza puntualmente. *La apertura de puertas y el ingreso de los visitantes, se realizará 15 minutos antes de la hora de inicio de cada recorrido.</p> 
   <p>*En caso de que tu visita sea suspendida o cancelada por temas de fuerza mayor o caso fortuito, te informaré con antelación al correo electrónico registrado en la inscripción. *Te invito a utilizar transporte público y alternativas de transporte sostenible, porque no se permitirá el ingreso de vehículos al interior de la Compañía, ni tampoco podrán estacionarse en la calle #4.</p> 
   <p>*Si vienes en vehículo propio, podrás estacionarlo en el parqueadero de Coltabaco, ubicado en la Cra 50 No. 5 - 175, y en el Parqueadero habilitado ubicado en la Calle #2.</p> 
</body>

</html>
