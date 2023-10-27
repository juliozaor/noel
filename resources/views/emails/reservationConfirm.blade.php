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
    <div  style="background: #EE2825; color: #fff;">
        @foreach ($codes as $code)
            @if ($code['isUser'] == 1)
                <span style="margin-left: 20px; font-weight: 600;font-size: 24px;">Hola, {{ $code['name'] }}</span>
            @endif
        @endforeach


    </div>

    <div style="
    background-color: #EEEEEE;
    margin: 10px;
    border-radius: 12px;
    padding: 20px;">
        <div>
            @foreach ($codes as $code)
                @if ($code['isUser'] == 1)
                <div style="display: flex;
                text-align: center;
                align-items: center;
                justify-content: center;"> 
                        <div style="text-align: center; margin-right: 30px; margin-left: 20px;">
                            <h3 style="color: red;"> Información de tu reserva</h3>
                            <p style="font-size: 20px"><strong>{{ $code['quota'] }} cupos</strong></p>
                            <p><strong>Fecha: {{ date('d-m-Y', strtotime($code['date'])) }}</strong></p>
                            <p><strong>Hora: {{ date('h:i A', strtotime($code['time'])) }}</strong></p>
                        <p>Presenta el codigo QR correspondiente a cada acompañante al momento del ingrego</p>
                        </div>
                        <div >
                           
                            <img src="https://backoffice.navidadesnoel.com/{{$code['qr']}}" alt="{{ $code['name'] }}">
                        </div>
                    </div>
                   
                @endif
            @endforeach
        </div>
    </div>
        <span style="color: #7a7979;
        direction: ltr;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: normal;
        line-height: 120%;
        text-align: left;">Acompañantes registrados</span><br>
    <div style=" margin: 10px 10px;display: flex;flex-wrap: wrap;">
        @foreach ($codes as $code)
        @if ($code['isUser'] == 0)
            <div style="padding: 10px;border-radius: 5px;background-color: white;max-width: 200px;">
                
                <div style="margin: 10px 10px">
                  

                <img src="https://backoffice.navidadesnoel.com/{{$code['qr']}}" alt="{{ $code['name'] }}">

                </div>
                <div style="font-weight: 400;font-size: 18px;margin-bottom: 10px; text-align: center;">
                    {{ $code['name'] }}
                </div>

            </div>
            @endif
        @endforeach
    </div>

    <div style="margin: 20px 25px">
        <p style="font-size: 18px;"><strong>RECOMENDACIONES DE INGRESO</strong></p>
        <p>Para disfrutar de esta divertida experiencia de forma segura, ten en cuenta las siguientes recomendaciones:
        </p>
        <ul>
            <li>Esta experiencia será realizada en Mundo NOEL, en las instalaciones de la Planta de Producción de Compañía
                De
                Galletas NOEL S.A.S. ubicada en la Carrera 52 # 2 - 28 en Medellín, Colombia. </li>
            <li>Este pase de ingreso no tiene valor comercial, es personal e intransferible y será requerido de forma
                digital o
                impreso por nuestro personal de seguridad, para facilitar tu ingreso a mi Compañía.</li>
            <li>Debes estar en la portería número 4 de NOEL, ubicada sobre la calle #4, con mínimo 30 minutos de antelación
                porque
                el recorrido empieza puntualmente. *La apertura de puertas y el ingreso de los visitantes, se realizará 15
                minutos
                antes de la hora de inicio de cada recorrido.</li>
            <li>En caso de que tu visita sea suspendida o cancelada por temas de fuerza mayor o caso fortuito, te informaré
                con
                antelación al correo electrónico registrado en la inscripción. *Te invito a utilizar transporte público y
                alternativas de transporte sostenible, porque no se permitirá el ingreso de vehículos al interior de la
                Compañía, ni
                tampoco podrán estacionarse en la calle #4.</li>
            <li>Si vienes en vehículo propio, podrás estacionarlo en el parqueadero de Coltabaco, ubicado en la Cra 50 No. 5
                -
                175, y en el Parqueadero habilitado ubicado en la Calle #2.</li>
        </ul>
        

    </div>
</body>

</html>
