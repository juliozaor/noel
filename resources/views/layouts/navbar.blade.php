<nav class="navbar bg-light w-100 pe-3">
  <div class="container-fluid d-flex">
    <!-- <a class="navbar-brand">Navbar</a> -->
    <div>
      <span class="material-symbols-outlined" id="alternar" (click)="this.abrirMenuLateral()">
        menu
      </span>
    </div>
    <div class="d-flex align-items-center mb-0 flex-gap-10">
        <img [src]="this.cabeceraModulo[1]">
        <h4 class="fs-16px fw-semibold" style="color:white;"></h4>
      </div>
    <ul class="d-flex align-items-center mb-0 ms-auto flex-gap-10">
      <li class="d-flex align-items-center fs-12px">
        <span style="color:white;"> <b>Hola</b>,{{ Auth::user()->name }} </span>
      </li>
      <li ngbDropdown
          #opcionesDeUsuario="ngbDropdown"
          display="dynamic"
          class="d-flex align-items-center rounded-circle fondo-gris icono-usuario fs-12px" width="100%">
        <a ngbDropdownToggle width="100%">
          <img class="img-fluid  items" src="{{ asset('/assets/icons/icono-usuario.svg') }}">
          <div ngbDropdownMenu aria-labelledby="navbarDropdown3" class="dropdown-menu fs-12px">
            <div>
              <a ngbDropdownItem><b>ROL:</b></a>
            </div>
            <div class="items d-flex align-items-center mb-0 fw-semibold">
              <img class="img-fluid" style="margin-left:15px;" src="assets/img/logo-usuario-dropdown.svg" alt="">
              <a ngbDropdownItem routerLink="configuracion_de_cuenta"  class="selector fw-semibold">Configuración de cuenta</a>
            </div>
            <div class="items d-flex align-items-center mb-0" >
              <img class="img-fluid" style="margin-left:15px;" src="assets/img/logo-salir-dropdown.svg" alt="">
              <a ngbDropdownItem (click)="this.cerrarSesion()"  class="selector fw-semibold">Salir</a>
            </div>
          </div>
        </a>
      </li>
    </ul>
  </div>
</nav>
