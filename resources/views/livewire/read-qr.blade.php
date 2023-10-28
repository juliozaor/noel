<div>

  @if($qrCodes)
    @if ($isValid)
    <div class="card text-center">
        <div class="card-header">
          Validar entrada de <strong>{{$name }}</strong>, con identificación <strong>{{$document}}</strong>
        </div>
        <div class="card-body">
          <svg class="h-20 w-20 text-green-600  mx-auto"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />  <polyline points="22 4 12 14.01 9 11.01" /></svg>
          <p class="card-text">Al hacer clic en aceptar el codigo qr será deshabilitado</p>

          <a href="#" class="btn btn-primary" wire:click="save({{ $qrCodes->id }})">Aceptar</a>
        </div>
      </div>

      @else
      <div class="card text-center">
        <div class="card-header">
          Validando entrada de <strong>{{$name }}</strong>, con identificación <strong>{{$document}}</strong>
        </div>
        <div class="card-body">
          <svg class="h-20 w-20 text-custom-yellow-500 mx-auto"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2" />  <line x1="12" y1="8" x2="12" y2="12" />  <line x1="12" y1="16" x2="12.01" y2="16" /></svg>
          <p class="card-text">Este QR ya fue leido el {{ date('d-m-Y', strtotime($qrCodes->updated_at)) }} a las {{ date('h:i A', strtotime($qrCodes->updated_at)) }}</p>
        </div>
      </div>
    @endif
   @else
   <div class="card text-center">
    <div class="card-header">
      Validando entrada QR errado
    </div>
    <div class="card-body">
      <svg class="h-20 w-20 text-red-600  mx-auto"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>     
      <p class="card-text">Este QR no fue generado por nuestro sistema de acceso</p>
    </div>
  </div>
   @endif


</div>
