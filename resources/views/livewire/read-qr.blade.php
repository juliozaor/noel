<div>

    @if ($isValid)
    <div class="card text-center">
        <div class="card-header">
          Validar entrada de <strong>{{$name }}</strong>, con identificación <strong>{{$document}}</strong>
        </div>
        <div class="card-body">
          <p class="card-text">Al hacer clic en aceptar el codigo qr será deshabilitado</p>

          <a href="#" class="btn btn-primary" wire:click="save({{ $qrCodes->id }})">Aceptar</a>
        </div>
      </div>

      @else
      <div class="card text-center">
        <div class="card-header">
          Validar entrada
        </div>
        <div class="card-body">
          <p class="card-text">Este código ya fue usado</p>
        </div>
      </div>
    @endif


</div>
