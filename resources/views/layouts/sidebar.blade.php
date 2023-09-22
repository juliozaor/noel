<aside class="menu-lateral p-3 d-flex flex-column">
  <!-- logo novafianza -->
  <div class="logo d-flex justify-content-start mb-4">
    <img class="pointer-events-none cursor-pointer" src="{{ asset('/assets/logo/logo2.png') }}" alt="">
  </div>
  
    <div class="d-flex flex-column">
     {{--  <hr>      
        <a href="{{ route('profile.show') }}" class="link_side">
        <span class="me-2">
          <img draggable="false" src="{{ asset('/assets/icons/adminUsers.svg') }}"[alt]="module._nombreMostrar">
        </span>
        <span class="fs-12px fw-semibold">
        Administrar usuarios
      </span>
    </a> --}}

    <hr>      
        <a href="{{ route('admin.events.register') }}" class="link_side {{request()->routeIs('admin.events.register') ? 'active':''}}">
        <span class="me-2">
          <img draggable="false" src="{{ asset('/assets/icons/registerEvents.svg') }}"[alt]="module._nombreMostrar">
        </span>
        <span class="fs-12px fw-semibold">
        Registro a eventos
      </span>
    </a>

    <hr>      
        <a href="{{ route('admin.events.index') }}" class="link_side {{request()->routeIs('admin.events.index') ? 'active':''}}">
        <span class="me-2">
          <img draggable="false" src="{{ asset('/assets/icons/adminEvents.svg') }}"[alt]="module._nombreMostrar">
        </span>
        <span class="fs-12px fw-semibold">
        Administrar eventos
      </span>
    </a>

    {{-- <hr>      
        <a href="#" class="link_side">
        <span class="me-2">
          <img draggable="false" src="{{ asset('/assets/icons/reports.svg') }}"[alt]="module._nombreMostrar">
        </span>
        <span class="fs-12px fw-semibold">
        Reportes
      </span>
    </a> --}}
    
    </div>



</aside>