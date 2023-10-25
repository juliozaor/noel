<div>
    <div class="d-flex flex-column flex-gap-30 bodyPage">
        <div class="row">
            <div class="col-12">
                <div class="card w-100 ">
                    <div class="card-header fw-bold fs-14px mt-2">
                        {{ $title }}
                    </div>
                    <div class="card-body">
                        <div class="contenedor pt-2 px-4 pb-4">
                            <div class="barraBusqueda">
                                <input type="text" class="form-control" placeholder="Buscar..." wire:model="search">
                            </div>
                            <div>
                                <button class="totales" disabled>
                                    <span class="fw-semibold fs-14px">Total:</span>
                                    <span class="fs-12px fw-semibold"style="margin-left: 5px">{{ $count }}</span>
                                </button>
                            </div>
                            <div class="d-flex justify-self-end flex-gap-5 ms-auto mb-3">
                                <span class="fs-12px d-flex align-items-center mx-2" id="registrosTotales">Registros por
                                    página</span>
                                <span class="texto-gris fs-12px fw-bold align-items-center">
                                    <select wire:model="cant" class="form-select sm" name="" id="">
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
                                        <th scope="col" class="cursor-pointer"
                                            wire:click="order('profiles.document')">
                                            Identificación
                                            <i class="fas fa-sort ml-1 mt-1"></i>
                                        </th>
                                        <th scope="col" class="cursor-pointer" wire:click="order('users.name')">
                                            Usuario
                                            <i class="fas fa-sort ml-1 mt-1"></i>
                                        </th>
                                        <th scope="col" class="cursor-pointer" wire:click="order('profiles.is_collaborator')">
                                            Tipo usuario
                                            <i class="fas fa-sort ml-1 mt-1"></i>
                                        </th>
                                        <th scope="col" class="cursor-pointer" wire:click="order('profiles.cell')">
                                            Telefono
                                            <i class="fas fa-sort ml-1 mt-1"></i>
                                        </th>

                                        <th colspan="2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($users && count($users) > 0)
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $user->document }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->is_collaborator == 1?'Colaborador':'Usuario' }}</td>
                                                <td>{{ $user->cell }}</td>
                                                <td>

                                                    <button wire:click="editUser({{ $user->document }})"><img
                                                            src="{{ asset('/assets/icons/edit.svg') }}" alt="editar"
                                                            style="max-width: 15px" /></button>
                                                </td>
                                                <td>

                                                    <button wire:click="deleteUser({{ $user->id }})"><img
                                                            src="{{ asset('/assets/icons/trash.svg') }}" alt="editar"
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
                    </div>
                    @if ($users && $users->hasPages())

                        <div class="card-footer d-flex justify-content-end " wire:key="list-users">
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
                                            <a class="page-link" href="#"
                                                wire:click="gotoPage({{ $page }})">{{ $page }}</a>
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
            </div>
        </div>
    </div>

{{-- Modal edicion --}}
<x-dialog-modal-j wire:model="openEditRegister">
    <x-slot name="title">
        <div class="d-flex justify-content-between">
            <div>
                Nuevo registro <span class="font-small text-red-600">- Todos los campos son obligatorios</span>
            </div>
           
           
            <button wire:click=close class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </x-slot>
    <x-slot name="content">

        <div class="d-flex pt-4 px-6 ps-4 pe-4 ">
            <div class="mr-2 w-25">
                <x-label value="Documento de identidad" />
                <x-input type="text" class="w-100" wire:model.defer="document" wire:blur="searchProfile" disabled/>
                <x-input-error for="document" />

            </div>
            <div class="mr-2 w-25">
                <x-label value="Nombre Completo" />
                <x-input type="text" class="w-100" wire:model.defer="name" />
                <x-input-error for="name" />
            </div>
            <div class="mr-2 w-25">
                <x-label value="Celular" />
                <x-input type="text" class="w-100" wire:model.defer="cell" />
                <x-input-error for="cell" />
            </div>
            <div class="mr-2 w-25">
                <x-label value="Dirección" />
                <x-input type="text" class="w-100" wire:model.defer="address" />
                <x-input-error for="address" />
            </div>
        </div>

        <div class="d-flex pt-4 px-6 ps-4 pe-4 ">
            <div class="mr-2 w-25">
                <x-label value="Barrio" />
                <x-input type="text" class="w-100" wire:model.defer="neighborhood" />
                <x-input-error for="neighborhood" />
            </div>
            <div class="mr-2 w-25">
                <x-label value="Fecha de nacimiento" />
                <x-input type="date" class="w-100" wire:model.defer="birth" />
                <x-input-error for="birth" />
            </div>
            <div class="mr-2 w-25">
                <x-label value="Correo electrónico empresarial" />
                <x-input type="text" class="w-100" wire:model.defer="email" disabled/>
                <x-input-error for="email" />
            </div>
            <div class="mr-2 w-25">
                <x-label value="EPS" />
                <div class="mt-2 ">
                    Si <input wire:model="epsState" name="epsState" type="radio" value="1" class="mr-2" />
                    No <input wire:model="epsState" name="epsState" type="radio" value="0" />
                </div>
            </div>
        </div>

        <div class="d-flex pt-4 px-6 ps-4 pe-4 ">
            <div class="mr-2 w-25">
                <x-label value="¿Cuál?" />
                <input type="text"
                    class="w-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    wire:model.defer="eps" @if ($epsState !== '1') disabled @endif />
                <x-input-error for="eps" />
            </div>
            <div class="mr-2 w-25">
                <x-label value="¿Estuviste en nuestra Experiencia 2022?" />
                <div class="mt-2">
                    Si <input wire:model.defer="experience2022" name="experience2022" type="radio" value="1"
                        class="mr-2" />
                    No <input wire:model.defer="experience2022" name="experience2022" type="radio"
                        value="0" />
                </div>
            </div>
            <div class="mr-2 w-25">
                <x-label value="¿Como te enteraste de este evento?" />
                {{-- <x-input type="date" class="w-100" wire:model.defer="reference" /> --}}
                <select wire:model="reference" class="form-control">
                    <option value="1">Redes Sociales de la Compañia</option>
                    <option value="2">Amigo o Familia</option>
                    <option value="3">Búsqueda en Internet</option>
                    <option value="4">Medios de comunicación y Publicidad</option>
                    <option value="5">Otro</option>

                </select>
                <x-input-error for="reference" />
            </div>
            <div class="mr-2 w-25">

            </div>

        </div>



    </x-slot>



    <x-slot name="footer" class="bg-success">
        <x-secondary-button wire:click=close>
            Cancelar
        </x-secondary-button>
        <x-danger-button wire:click=saveUpdateContinue>
            Guardar cambios
        </x-danger-button>

    </x-slot>
</x-dialog-modal-j>





    @push('js')
        <script src="sweetalert2.all.min.js"></script>
        <script>
            Livewire.on('delUser', userId => {
                Swal.fire({
                    title: 'Se eliminará el usuario',
                    text: "¿Desea continuar?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, eliminar'
                }).then((result) => {
                    if (result.isConfirmed) {

                        Livewire.emitTo('users','confirmDelete',userId)
                        Swal.fire(
                            'Eliminado!',
                            'El usuario se ha eliminado.',
                            'success'
                        )
                    }
                });

            });
        </script>
    @endpush
</div>
