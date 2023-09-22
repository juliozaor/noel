<div>
    <x-dialog-modal-j wire:model="openRegisterUserInEvent" >
        <x-slot name="title">
            <div class="d-flex justify-content-between">
                <span>Registrar usuario en evento 1</span>
                <button wire:click="$set('openRegisterUserInEvent', false)" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="encabezadoRegistro">
                <div class="d-flex w-50 pt-4 px-6 pb-4">
                    <div class="mr-2 w-25">
                        <strong>Fecha:</strong><br>
                        <span>05/11/2022</span>
                    </div>
                    <div class="mr-2 w-25">
                        <strong>Hora:</strong><br>
                        <span>3:30 PM</span>
                    </div>
                    <div class="mr-2 w-25">
                        <strong>Cupos totales:</strong><br>
                        <span>1000</span>
                    </div>
                    <div class="mr-2 w-25">
                        <strong>Cupos disponibles:</strong><br>
                        <span>50</span>
                    </div>
                </div>
            </div>

            <ul class="nav nav-pills encabezadoTabs mb-3 pt-4 px-6" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                        aria-selected="true">Lista de usuarios</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                        aria-selected="false">Lista de espera</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact"
                        aria-selected="false">Usuarios registrados</button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                    aria-labelledby="pills-home-tab">
                    <div class="contenedor pt-2 px-4 pb-4">
                        <div class="barraBusqueda">
                            <input type="text" class="form-control" placeholder="Buscar..." wire:model="search" wire:input="onSearchChange">
                        </div>
                        <div>
                            <button class="totales" disabled>
                                <span class="fw-semibold fs-14px">Total:</span>
                                <span class="fs-12px fw-semibold"style="margin-left: 5px">{{ $countUser }}</span>
                            </button>
                        </div>
                        <div class="d-flex justify-self-end flex-gap-5 ms-auto mb-3">
                            <span class="fs-12px d-flex align-items-center mx-2" id="registrosTotales">Registros por
                                página</span>
                            <span class="texto-gris fs-12px fw-bold align-items-center">
                                <select wire:model="cantUser" class="form-select sm" name="" id="" onchange="changeCantUser(event)">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                </select>
                            </span>
                        </div>
                    </div>
                    <div class="container-table d-flex table-responsive pt-2 px-4">
                        <table class="table table-striped fs-12px">
                            <thead>
                                <tr>
                                    <th scope="col" class="cursor-pointer" wire:click="order('profiles.document')">
                                        Identificación
                                        <i class="fas fa-sort ml-1 mt-1"></i>
                                    </th>
                                    <th scope="col" class="cursor-pointer" wire:click="order('users.name')">
                                        Usuario
                                        <i class="fas fa-sort ml-1 mt-1"></i>
                                    </th>
                                    <th scope="col" class="cursor-pointer" wire:click="order('profiles.cell')">
                                        Telefono
                                        <i class="fas fa-sort ml-1 mt-1"></i>
                                    </th>
    
                                    <th scope="col" >Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($users && count($users) > 0)
                                @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->document }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->cell }}</td>
                                    <td>
                                                
                                        <button wire:click="editRegisterUser({{ $user->document}})"><img
                                                src="{{ asset('/assets/icons/usermas.svg') }}" alt="editar"
                                                style="max-width: 15px" /></button>
                                    </td>
                                    
                                </tr>
                            @endforeach
                            @else
                                        <td colspan="6">
                                            <label class="d-flex justify-content-center fs-14px">
                                                No se encontraron datos
                                            </label>
                                        </td>
                                    @endif
                              
                            
                           
    
                            </tbody>
    
                        </table>
                        
                    </div>
                    @if ($users && $users->hasPages())
                    {{-- <div class="card-footer d-flex justify-content-end " wire:key="register-users">
                        {{ $users->links() }}

                    </div> --}}
                    <div class="card-footer d-flex justify-content-end " wire:key="register-users">
                        <ul class="pagination">
                            {{-- Botón "Anterior" --}}
                            @if ($users->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">Anterior</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="#" wire:click="previousPage">Anterior</a>
                                </li>
                            @endif
                    
                            {{-- Enlaces de páginas --}}
                            @for ($page = 1; $page <= $users->lastPage(); $page++)
                                @if ($page == $users->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="#" wire:click="gotoPage({{ $page }})">{{ $page }}</a>
                                    </li>
                                @endif
                            @endfor
                    
                            {{-- Botón "Siguiente" --}}
                            @if ($users->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="#" wire:click="nextPage">Siguiente</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">Siguiente</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
                </div>
                
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    ...</div>
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                    ...</div>
            </div>







        </x-slot>

        <x-slot name="footer" class="bg-success">
        </x-slot>
    </x-dialog-modal-j>
    <script>
        function changeCantUser(event) {
            const newValue = event.target.value;
            Livewire.emit('changeCantUser', newValue);
        }
    </script>
</div>
